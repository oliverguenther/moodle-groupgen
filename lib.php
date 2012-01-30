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



defined('MOODLE_INTERNAL') || die;


/**
 * Navigation hook
 */
function report_groupgen_extend_navigation_course($navigation, $course, $context) {
    global $CFG, $OUTPUT;
    if (has_capability('report/groupgen:view', $context)) {
        $url = new moodle_url('/report/groupgen/index.php', array('id'=>$course->id));
        $navigation->add(get_string('pluginname', 'report_groupgen'), $url, navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
    }
}
/**
 * Generate a group, given the groupname
 *
 * @param string $groupname
 * @return TRUE if group has been created, FALSE otherwise
 */
function report_groupgen_generategroup($groupname) {
}

/**
 * Generate a group, given the groupname and enroll the user with id $id into that group
 *
 * @param string $groupname
 * @param int $id
 * @return TRUE if group has been created and user has been enrolled, FALSE otherwise
 * 
 */
function report_groupgen_generategroup_and_enroll($groupname) {
}
