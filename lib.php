<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains functions used by the groupgen tools
 *
 * @since 2.0
 * @package course-report
 * @copyright 2012 Olexandr Savchuk, Oliver Guenther
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
 require_once($CFG->dirroot . '/group/lib.php');

/**
 * This function extends the navigation with the report items
 *
 * @param navigation_node $navigation The navigation node to extend
 * @param stdClass $course The course to object for the report
 * @param stdClass $context The context of the course
 */
function groupgen_report_extend_navigation($navigation, $course, $context) {
    global $CFG, $OUTPUT;
    if (has_capability('coursereport/groupgen:view', $context)) {
        $url = new moodle_url('/course/report/groupgen/index.php', array('id'=>$course->id));
        $navigation->add(get_string('menu_text', 'coursereport_groupgen'), $url, navigation_node::TYPE_SETTING, null, null, new pix_icon('i/users', ''));
    }
}

/**
 * Return a list of page types
 * @param string $pagetype current page type
 * @param stdClass $parentcontext Block's parent context
 * @param stdClass $currentcontext Current context of block
 */
function groupgen_page_type_list($pagetype, $parentcontext, $currentcontext) {
    $array = array(
        '*' => get_string('page-x', 'pagetype'),
        'course-report-*' => get_string('page-course-report-x', 'pagetype'),
        'course-report-outline-index' => get_string('pluginpagetype',  'coursereport_outline')
    );
    return $array;
}

/**
 * Create a new group in the course. Returns the new group ID.
 */
function groupgen_create_group($course, $groupname) {
	$data = new stdClass();
	
	$data->name = $groupname;
	$data->courseid = $course->id;

	return groups_create_group($data);
}
/**
 * @return boolean True if user added successfully or the user is already a
 * member of the group, false otherwise.
 */
function groupgen_enlist_user($groupid, $userid) {
	groups_add_user($groupid, $userid);
}