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

$id         = required_param('id', PARAM_INT); // course id.
$action     = optional_param('action', '', PARAM_ALPHA);

$url = new moodle_url('/report/groupgen/index.php', array('id'=>$id));
if ($action !== '') $url->param('action');
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
echo $OUTPUT->header();

$mform = new report_groupgen_form();
if ($fromform=$mform->get_data()){
	// TODO 
	print_r($fromform);
} else {

	$enrolled = get_enrolled_users($context, '', 0, 'u.id, u.firstname, u.lastname');
	$list = array();
	foreach ($enrolled as $user) {
		$list[$user->id] = "$user->lastname, $user->firstname";
	}
	// Set enrolled users
	$defaults = new stdClass;
	$defaults->enroll_tutor_select = $list;
	$defaults->counter_enabled = 1;

	$mform->set_data($defaults);
    $mform->display();
	$foo = $mform->get_data();
}

echo $OUTPUT->footer();
