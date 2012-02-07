<?php
// Moodle Groupgen coursereport - Generate groups based on time slices
// Copyright (C) 2012 Olexandr Savchuk, Oliver Günther

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Groupgen plugin
 *
 * @package    report
 * @subpackage groupgen
 * @copyright  2012 onwards Olexandr Savchuk, Oliver Günther
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once('report_groupgen_form.php');
require_once('lib.php');

$id         = required_param('id', PARAM_INT); // course id.
$confirmed     = optional_param('confirmed', '', PARAM_INT);

$url = new moodle_url('/report/groupgen/index.php', array('id'=>$id));
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');

if (!$course = $DB->get_record('course', array('id'=>$id))) {
    print_error('invalidcourse');
}

require_login($course);
$context = get_context_instance(CONTEXT_COURSE, $course->id);
require_capability('report/groupgen:view', $context);

add_to_log($course->id, "course", "groupgen view", "report/groupgen/index.php?id=$course->id", $course->id);

$PAGE->set_title($course->shortname .': Groupgen');
$PAGE->set_heading($course->fullname);

$mform = new report_groupgen_form();
$confirmerrors = array();
if ($confirmed === 1) {
	
	// Retrieve groups
	$groups = optional_param_array('group', '', PARAM_RAW);
	
	// Optinal grouping ID and new name
	$grouping = optional_param('grouping', '', PARAM_INT);
	$grouping_new_name = optional_param('grouping_new', '', PARAM_TEXT);

	// Optional user id to enroll into all groups
	$enrolltutorid = optional_param('enrolltutor', '', PARAM_INT);

	if (!empty($groups)) {
		$success = true;
		
		// Create new grouping
		if (!empty($grouping_new_name)) {
			$grouping = report_groupgen_generategrouping($grouping_new_name, $course);
			if (!$grouping) {
				$confirmerrors[] = get_string('creategrouping_failed', 'report_groupgen', $grouping_new_name);
				$success = false;
			}
		}
		
		// Create groups
		foreach ($groups as $groupname) {

			// Create group
			$gid = report_groupgen_generategroup($groupname, $course);
			if ($gid === false) {
				$confirmerrors[] = get_string('creategroup_failed', 'report_groupgen', $groupname);
				$success = false;
			} else {
				// Enroll tutor in group
				if (!empty($enrolltutorid) && !report_groupgen_enlist_user($gid, $enrolltutorid)) {
					$confirmerrors[] = get_string('enrolltutor_failed', 'report_groupgen', $enrolltutorid, $groupname);
				}
				
				// Add group to grouping
				if (!empty($grouping) && $grouping > 0 && !groups_assign_grouping($grouping, $gid)) {
					$confirmerrors[] = get_string('assigngrouping_failed', 'report_groupgen', $groupname);
				}
			}
		}
		if ($success) {
			// Forward to groups
			$courseurl = new moodle_url("/group/index.php?id=$course->id");
			redirect($courseurl);
		}

	}

} 

echo $OUTPUT->header();

if (!empty($confirmerrors)) {
	echo '<h1>' . get_string('error') . '</h1>';
	echo '<p class="error">' . implode("<br/>", $confirmerrors) . '</p>';
}

if ($data = $mform->get_data()) {

	$template = $data->groupname_template;
	$starttime = $data->timeslices_starttime;
	$endtime = $data->timeslices_endtime;
	$duration = $data->timeslices_duration;
	$offset = isset($data->timeslices_offset) ? $data->timeslices_offset : 0;
	$counter = isset($data->counter_offset) ? $data->counter_offset : -1;
	$enrolltutor = isset($data->enroll_tutor_enabled) ? $data->enroll_tutor_select : null;
	$grouping = isset($data->grouping_enabled) ? $data->grouping_select : null;
	
	// Get grouping name from ID or new name
	if ($grouping > 0) {
		$grouping_record = $DB->get_record('groupings', array('id'=>$grouping), '*', MUST_EXIST);
		$grouping_name = $grouping_record->name;
		$grouping_new = false;
	} else if ($grouping == 0 && $data->grouping_new_name) {
		$grouping_name = $data->grouping_new_name;
		$grouping_new = true;
	}
	
	// Get tutor name if provided
	if(isset($enrolltutor))
		$tutor = $DB->get_record('user', array('id'=>$enrolltutor), '*', MUST_EXIST);

	// Preview group generation to allow corrections
	$groups = report_groupgen_generate_groups_timeslice_posix(
		$template,
		$starttime,
		$endtime,
		$duration,
		$offset,
		$counter
	);

	// Append for duplicates
	$groups = report_groupgen_check_duplicates($groups, $course);
	
	// Print table for confirmation
	$attributes = array('id' => 'report_groupgen_confirm', 'method' => 'POST', 'action' => $url);
	$gt = html_writer::start_tag('form', $attributes);
	$gt .= html_writer::start_tag('table'); // </TABLE>
    $gt .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'confirmed', 'value' => 1));       

	$gt .= html_writer::start_tag('tr');
	$gt .= html_writer::tag('th', get_string('groupname', 'report_groupgen'));
	$gt .= html_writer::end_tag('tr');

	$available = 0;
	foreach ($groups as $i => $group) {
		$gt .= html_writer::start_tag('tr');
		if (empty($group->exists)) {
			$gt .= html_writer::tag('td', $group->groupname, array('class' => 'groupgen_okay'));
		    $gt .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => "group[$i]", 'value' => $group->groupname));       
			$available++;
		} else {
			$gt .= html_writer::tag('td', $group->groupname . " " . get_string('groupgen_group_exists', 'report_groupgen'), array('class' => 'groupgen_error'));
		}
		$gt .= html_writer::end_tag('tr');
	}
	$gt .= html_writer::end_tag('table'); // </TABLE>
	
	if (isset($enrolltutor)) {
		$gt .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'enrolltutor', 'value' => $enrolltutor));
		$gt .= html_writer::tag('p', get_string('enroll_tutor_enabled', 'report_groupgen').': <b>'.$tutor->lastname.', '.$tutor->firstname.'</b>', array());
	}
	
	if (isset($grouping)) {
		$gt .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'grouping', 'value' => $grouping));
		if ($grouping_new)
			$gt .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'grouping_new', 'value' => $grouping_name));
		$gt .= html_writer::tag('p', get_string('grouping', 'report_groupgen').': <b>'.$grouping_name.'</b> '.($grouping_new ? get_string('grouping_create_new', 'report_groupgen') : ''), array());
	}
	
	if ($available == 0) {
		$gt .= html_writer::tag('p', get_string('confirm_disabled', 'report_groupgen'), array('class' => 'groupgen_error')) ;
	} else {
    	$gt .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>get_string('confirm')));
	}
	$gt .= html_writer::end_tag('form');

	echo $gt;

	
}

$mform->display();
echo $OUTPUT->footer();
