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
 * Language definitions (en)
 *
 * @package    report
 * @subpackage groupgen
 * @copyright  2012 onwards Olexandr Savchuk, Oliver Günther
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// META
$string['pluginname'] = 'Groupgen';

// Timeslices
$string['timeslices'] = 'Timeslices';
$string['timeslices_header'] = 'Timeslices';
$string['timeslices_enabled'] = 'Enable timeslices';
$string['timeslices_enabled_help'] = 'Enable timeslices to create a certain amount of groups, based on the settings below';
$string['timeslices_starttime'] = 'Starting time';
$string['timeslices_starttime_help'] = 'Set this to the time where the slicing should start.';
$string['timeslices_endtime'] = 'Ending time';
$string['timeslices_endtime_help'] = 'Set this to a time where the last slice should stop.';
$string['timeslices_offset'] = 'Offset between slices';
$string['timeslices_offset_help'] = '(Optional) Specify an offset in minutes between each slice';

// counter
$string['counter_header'] = 'Counter';
$string['counter_enabled'] = 'Enable counter';
$string['counter_enabled_help'] = 'Enable this in order to use a counter in the groupname template';
$string['counter_offset'] = 'Counter start value';
$string['counter_offset_help'] = '(Optional) Specify a start value for the counter';

// templates
$string['template_header'] = 'Template specification';
$string['groupname_template'] = 'Group name template';
$string['groupname_template_p'] = 'Set the group name using these templates: #{counter}, #{timeslice}, ...';
$string['groupname_template_help'] = 'Specify the group identifier using the mentioned templates/variables';
