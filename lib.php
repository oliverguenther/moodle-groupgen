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
 * Groupgen backend
 *
 * @package    report
 * @subpackage groupgen
 * @copyright  2012 onwards Olexandr Savchuk, Oliver Günther
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
require_once($CFG->dirroot . '/group/lib.php');

defined('MOODLE_INTERNAL') || die;


/**
 * Navigation hook
 */
function report_groupgen_extend_navigation_course($navigation, $course, $context) {
    global $CFG, $OUTPUT;
    if (has_capability('report/groupgen:view', $context)) {
        $url = new moodle_url('/report/groupgen/index.php', array('id'=>$course->id));
        $navigation->add(get_string('pluginname', 'report_groupgen'), $url, navigation_node::TYPE_SETTING, null, null, new pix_icon('i/users', ''));
    }
}
/**
 * Generate a group, given the groupname
 *
 * @param string $groupname
 * @param stdClass $course
 * @return TRUE if group has been created, FALSE otherwise
 */
function report_groupgen_generategroup($groupname, $course) {
	$data = new stdClass();
	$data->name = $groupname;
	$data->courseid = $course->id;
	return groups_create_group($data);
}

// TODO: remove?
function report_groupgen_page_type_list($pagetype, $parentcontext, $currentcontext) {
    $array = array(
        '*' => get_string('page-x', 'pagetype'),
        'course-report-*' => get_string('page-course-report-x', 'pagetype'),
        'course-report-outline-index' => get_string('pluginpagetype',  'coursereport_groupgen')
    );
    return $array;
}

/**
 * @return boolean True if user added successfully or the user is already a
 * member of the group, false otherwise.
 */
function report_groupgen_enlist_user($groupid, $userid) {
	return groups_add_user($groupid, $userid);
}

/** Transforms a hh:mm time string into an int with value 60*hh+mm */
function report_groupgen_string_to_time($string) {
	if (preg_match("/^[0-9]{2}\:[0-9]{2}$/", $string) == 0)
		return 0;
	$chunks = explode(':', $string);
	return $chunks[0] * 60 + $chunks[1];
}

/** Transforms a int with value 60*hh+mm into hh:mm time string */
function report_groupgen_time_to_string($time) {
	return sprintf("%02d:%02d", floor($time/60), $time % 60);
}

/**
 * Generate group names to fill the time span with given group lengths and pauses.
 * @param string $template Group name template, using #{start} and #{end} as placeholders
 * @param string $start Start of the time span, hh:mm
 * @param string $end End of the time span, hh:mm
 * @param string $duration Duration of a single group, hh:mm
 * @param string $pause Duration of a pause between groups, hh:mm
 * @param int $counter optional Number to assign to the first generated group
 * @return string[] Generated group names
 */
function report_groupgen_generate_groups_timeslice($template, $start, $end, $duration, $pause = "00:00", $counter = -1) {

	// init output array
	$groups = array();

	// prepare formatting data
	$needle = array('#{start}', '#{end}');
	if ($counter > -1) 
		$needle[] = '#{counter}';

	// transform input times into ints for easy calculation
	$time = report_groupgen_string_to_time($start);
	$time_end = report_groupgen_string_to_time($end);
	$time_duration = report_groupgen_string_to_time($duration);
	$time_pause = report_groupgen_string_to_time($pause);
	
	do {
		// group start and end times
		$group_start = $time;
		$group_end = $time += $time_duration;

		// format group name and add to results
		$replace = array(report_groupgen_time_to_string($group_start), report_groupgen_time_to_string($group_end));
		if ($counter > -1) 
			$replace[] = $counter++;
		$groups[] = str_replace($needle, $replace, $template);

		// add pause time between groups
		$time += $time_pause;
	} while ($time < $time_end);

	return $groups;
}
