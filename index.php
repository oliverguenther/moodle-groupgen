<?php

    require_once('../../../config.php');
    require_once($CFG->dirroot.'/course/lib.php');

    $id = required_param('id',PARAM_INT);       // course id

    if (!$course = $DB->get_record('course', array('id'=>$id))) {
        print_error('invalidcourseid');
    }

    $PAGE->set_url('/course/report/groupgen/index.php', array('id'=>$id));
    
    require_login($course);
    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    require_capability('coursereport/groupgen:view', $context);

    $showlastaccess = true;
    $hiddenfields = explode(',', $CFG->hiddenuserfields);

    $stractivityreport = get_string('activityreport');
    $stractivity       = get_string('activity');
    $strlast           = get_string('lastaccess');
    $strreports        = get_string('reports');
    $strviews          = get_string('views');
    $strrelatedblogentries = get_string('relatedblogentries', 'blog');

    $PAGE->set_title($course->shortname .': '. get_string('menu_text', 'coursereport_groupgen'));
    echo $OUTPUT->header();
    echo $OUTPUT->heading(format_string(get_string('menu_text', 'coursereport_groupgen')));
    
	

    echo $OUTPUT->footer();
