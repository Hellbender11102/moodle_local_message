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
 * message file description here.
 *
 * @package    local_message
 * @copyright  2021 SysBind Ltd. <service@sysbind.co.il>
 * @auther     schindlerl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/message/form/edit.php');

global $DB;

$PAGE->set_url(new moodle_url('/local/message/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Message edit');


$mform = new edit();


if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot . '/local/message/manage.php','Canceled');
} elseif ($fromform = $mform->get_data()) {
    //insert the data in the db
    $recordToInsert = new stdClass();
    $recordToInsert->messagetext = $fromform->messagetext;
    $recordToInsert->messagetype = $fromform->messagetype;

    $DB->insert_record('local_message',$recordToInsert);
    redirect($CFG->wwwroot . '/local/message/manage.php','You created a message with title '.  $fromform->messagetext);
}


echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();