<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot . '/course/moodleform_mod.php');

class report_groupgen_form extends moodleform {

    function definition() {
        global $CFG;

        $mform = & $this->_form;

//-------------------------------------------------------------------------------
		// TIMESLICES
        $mform->addElement('header', 'timeslices_header', get_string('timeslices_header', 'report_groupgen'));
		$mform->addElement('checkbox', 'timeslices_enabled', get_string('timeslices_enabled', 'report_groupgen'));
        $mform->addHelpButton('timeslices_enabled', 'timeslices_enabled', 'report_groupgen');

		// Start time
		$mform->addElement('date_time_selector', 'timeslices_starttime', get_string('timeslices_starttime', 'report_groupgen'));
        $mform->addHelpButton('timeslices_starttime', 'timeslices_starttime', 'report_groupgen');

		// End time
		$mform->addElement('date_time_selector', 'timeslices_endtime', get_string('timeslices_endtime', 'report_groupgen'));
        $mform->addHelpButton('timeslices_endtime', 'timeslices_endtime', 'report_groupgen');

		// offset between slices, in minutes
		$mform->addElement('text', 'timeslices_offset', get_string('timeslices_offset', 'report_groupgen'));
        $mform->addHelpButton('timeslices_offset', 'timeslices_offset', 'report_groupgen');

		// Disable all settings unless checked
        $mform->disabledIf('timeslices_starttime', 'timeslices_enabled', 'eq', '0');
        $mform->disabledIf('timeslices_endtime', 'timeslices_enabled', 'eq', '0');
        $mform->disabledIf('timeslices_offset', 'timeslices_enabled', 'eq', '0');

//-------------------------------------------------------------------------------
		// COUNTER
        $mform->addElement('header', 'counter_header', get_string('counter_header', 'report_groupgen'));
		$mform->addElement('checkbox', 'counter_enabled', get_string('counter_header', 'report_groupgen'));
        $mform->addHelpButton('counter_enabled', 'counter_enabled', 'report_groupgen');

		// Counter start offset
		$mform->addElement('text', 'counter_offset', get_string('counter_offset', 'report_groupgen'));
        $mform->addHelpButton('counter_offset', 'counter_offset', 'report_groupgen');

		// Disable all settings unless checked
        $mform->disabledIf('counter_offset', 'counter_enabled', 'eq', '0');


//-------------------------------------------------------------------------------
		// Let people combine their settings to the string they desire
        $mform->addElement('header', 'template_header', get_string('template_header', 'report_groupgen'));
		$mform->addElement('text', 'groupname_template', get_string('groupname_template', 'report_groupgen'));
		


//-------------------------------------------------------------------------------
        $this->add_action_buttons();
    }

    function validation($data, $files) {
        global $USER, $COURSE;
        $errors = parent::validation($data, $files);

		// TODO validation

        return $errors;
    }

}

