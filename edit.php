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


$PAGE->set_url(new moodle_url('/local/message/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('formTextfieldName', 'local_message'));


$mform = new edit();


if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('canceld', 'local_message'));
} elseif ($fromform = $mform->get_data()) {
    //insert the data in the db
    $messageManager = new message_manager();
    $messageManager->create_message($fromform->messagetext, $fromform->messagetype);

    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('updated', 'local_message') . ' ' . $fromform->messagetext);
}


echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
