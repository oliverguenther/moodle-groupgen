<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot . '/course/moodleform_mod.php');

class report_groupgen_form extends moodleform {

    function definition() {
        global $CFG, $course;

        $mform = & $this->_form;

//-------------------------------------------------------------------------------
		// Keep course id
        $mform->addElement('hidden', 'id', $course->id);
//-------------------------------------------------------------------------------
		// TIMESLICES
        $mform->addElement('header', 'timeslices_header', get_string('timeslices_header', 'report_groupgen'));
		$mform->addElement('checkbox', 'timeslices_enabled', get_string('timeslices_enabled', 'report_groupgen'));
        $mform->addHelpButton('timeslices_enabled', 'timeslices_enabled', 'report_groupgen');
		$mform->setDefault('timeslices_enabled', 1);

		// Start time
		$mform->addElement('date_time_selector', 'timeslices_starttime', get_string('timeslices_starttime', 'report_groupgen'));
        $mform->addHelpButton('timeslices_starttime', 'timeslices_starttime', 'report_groupgen');

		// End time
		$mform->addElement('date_time_selector', 'timeslices_endtime', get_string('timeslices_endtime', 'report_groupgen'));
        $mform->addHelpButton('timeslices_endtime', 'timeslices_endtime', 'report_groupgen');

		// Slices duration
		$mform->addElement('duration', 'timeslices_duration', get_string('timeslices_duration', 'report_groupgen'));
        $mform->addHelpButton('timeslices_duration', 'timeslices_duration', 'report_groupgen');

		// offset between slices
		$mform->addElement('duration', 'timeslices_offset', get_string('timeslices_offset', 'report_groupgen'));
        $mform->addHelpButton('timeslices_offset', 'timeslices_offset', 'report_groupgen');

		// Disable all settings unless checked
        $mform->disabledIf('timeslices_starttime', 'timeslices_enabled', 'notchecked');
        $mform->disabledIf('timeslices_endtime', 'timeslices_enabled', 'notchecked');
        $mform->disabledIf('timeslices_duration', 'timeslices_enabled', 'notchecked');
        $mform->disabledIf('timeslices_offset', 'timeslices_enabled', 'notchecked');

//-------------------------------------------------------------------------------
		// COUNTER
        $mform->addElement('header', 'counter_header', get_string('counter_header', 'report_groupgen'));
		$mform->addElement('checkbox', 'counter_enabled', get_string('counter_header', 'report_groupgen'));
        $mform->addHelpButton('counter_enabled', 'counter_enabled', 'report_groupgen');
		$mform->setDefault('counter_enabled', 1);

		// Counter start offset
		$mform->addElement('text', 'counter_offset', get_string('counter_offset', 'report_groupgen'));
        $mform->addHelpButton('counter_offset', 'counter_offset', 'report_groupgen');
		$mform->setDefault('counter_offset', 1);

		// Disable all settings unless checked
        $mform->disabledIf('counter_offset', 'counter_enabled', 'notchecked');


//-------------------------------------------------------------------------------
		// Let people combine their settings to the string they desire
        $mform->addElement('header', 'template_header', get_string('template_header', 'report_groupgen'));
		$mform->addElement('html', '<p>' . get_string('groupname_template_p', 'report_groupgen') . '</p>');
		$mform->addElement('text', 'groupname_template', get_string('groupname_template', 'report_groupgen'), 'size="100"');
		$mform->setDefault('groupname_template', 'Gruppen-Intervall Nr. #{counter}: #{start} - #{end}');
		
//-------------------------------------------------------------------------------
		// Allow one enlisted user to be enrolled in all groups (e.g., a tutor/mentor)

		// Fetch enrolled users
		$context = get_context_instance(CONTEXT_COURSE, $course->id);
		$enrolled = get_enrolled_users($context, '', 0, 'u.id, u.firstname, u.lastname');
		$list = array();
		foreach ($enrolled as $user) {
			$list[$user->id] = "$user->lastname, $user->firstname";
		}
        $mform->addElement('header', 'enroll_tutor_header', get_string('enroll_tutor', 'report_groupgen'));
		$mform->addElement('checkbox', 'enroll_tutor_enabled', get_string('enroll_tutor_enabled', 'report_groupgen'));
        $mform->addElement('select', 'enroll_tutor_select', get_string('enroll_tutor_select', 'report_groupgen'), $list);
        $mform->addHelpButton('enroll_tutor_select', 'enroll_tutor', 'report_groupgen');

        $mform->disabledIf('enroll_tutor_select', 'enroll_tutor_enabled', 'notchecked');


		

//-------------------------------------------------------------------------------
        $this->add_action_buttons();
    }

    function validation($data, $files) {
        global $USER, $COURSE;
        $errors = parent::validation($data, $files);

		if (isset($data['counter_enabled']) && !is_numeric($data['counter_offset']))
			$errors['counter_offset'] = get_string('error_counter_numeric', 'report_groupgen');

		if (!isset($data['counter_enabled']) && (strpos($data['groupname_template'], '#{counter}') !== false))
			$errors['counter_enabled'] = get_string('error_template_marker', 'report_groupgen', '#{counter}');


		$timeslices_marker_errors = array();
		if (!isset($data['timeslices_enabled']) && (strpos($data['groupname_template'], '#{start}') !== false))
			array_push($timeslices_marker_errors, get_string("error_template_marker", "report_groupgen", "#{start}"));
			
		if (!isset($data['timeslices_enabled']) && (strpos($data['groupname_template'], '#{end}') !== false))
			array_push($timeslices_marker_errors, get_string("error_template_marker", "report_groupgen", "#{end}"));
		
		if (!empty($timeslices_marker_errors))
			$errors['timeslices_enabled'] = implode("<br/>", $timeslices_marker_errors);

        return $errors;
    }

}

