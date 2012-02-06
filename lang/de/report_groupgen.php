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
 * Language definitions (de_du)
 *
 * @package    report
 * @subpackage groupgen
 * @copyright  2012 onwards Olexandr Savchuk, Oliver Günther
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// META
$string['pluginname'] = 'Groupgen';

// Timeslices
$string['timeslices_header'] = 'Zeitintervalle';
$string['timeslices_enabled'] = 'Intervalle aktivieren';
$string['timeslices_enabled_help'] = 'Die Gruppen werden anhand der Einstellungen in Zeitintervalle unterteilt und entsprechend benannt.';
$string['timeslices_starttime'] = 'Startzeitpunkt';
$string['timeslices_starttime_help'] = 'Setze dies auf den Startzeitpunkt der Intervalle';
$string['timeslices_endtime'] = 'Endzeitpunkt';
$string['timeslices_endtime_help'] = 'Setze dies auf die Zeit, an der das letzte Intervall enden soll';
$string['timeslices_duration'] = 'Dauer eines Intervalls';
$string['timeslices_duration_help'] = 'Wähle hier die Dauer <strong>eines</strong> Intervalls';
$string['timeslices_offset'] = 'Abstand zwischen Intervallen';
$string['timeslices_offset_help'] = '(Optional) Gebe hier einen Abstand zwischen jedem Intervall an';

// counter
$string['counter_header'] = 'Zähler';
$string['counter_enabled'] = 'Zähler aktivieren';
$string['counter_enabled_help'] = 'Der Zähler kann verwendet werden, um Gruppen durchzunummerieren';
$string['counter_offset'] = 'Startwert';
$string['counter_offset_help'] = '(Optional) Gebe hier einen Startwert an, ab dem gezählt werden soll';

// templates
$string['template_header'] = 'Vorlage';
$string['groupname_template'] = 'Vorlage für Gruppennamen';
$string['groupname_template_p'] = 'Setze eine Vorlage mithilfe dieser Variablen: #{counter}, #{start}, #{end}';
$string['groupname_template_help'] = 'Wähle die Vorlage der Gruppennamen mit den oben angegebenen Variablen.';

// tutor enroll
$string['enroll_tutor_enabled'] = 'Tutor in Gruppe einwählen';
$string['enroll_tutor'] = 'Tutor auswählen';
$string['enroll_tutor_select'] = 'Wähle einen Nutzer';
$string['enroll_tutor_help'] = 'Füge den ausgewählten Nutzer automatisch der Gruppe hinzu (z.B. den Tutor der Gruppe) ';

// Form validation / errors
$string['error_counter_numeric'] = "Der Zähler-Startwert muss numerisch sein!";
$string['error_template_marker'] = 'Um die Variable {$a} nutzen zu können, müssen Sie diese Option aktivieren!';

// Confirmation
$string['groupname'] = 'Zu erstellender Gruppenname';
$string['groupgen_group_exists'] = '(Gruppe existiert bereits)';
$string['confirm_disabled'] = 'Alle Gruppen existieren bereits. Kann nicht fortfahren';
$string['creategroup_failed'] = 'Konnte die Gruppe {$a} nicht anlegen.';
$string['enrolltutor_failed'] = 'Konnte den Nutzer {$a} nicht in die Gruppe {$b} einschreiben';
