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
 * ${PLUGINNAME} file description here.
 *
 * @package    local_message
 * @copyright  2021 SysBind Ltd. <service@sysbind.co.il>
 * @auther     schindlerl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function local_message_before_footer()
{
    global $USER;

    $messageManager = new message_manager();


    $messages = $messageManager->get_Massages($USER->id);

    $choices = array();
    $choices['0'] = \core\output\notification::NOTIFY_SUCCESS;
    $choices['1'] = \core\output\notification::NOTIFY_INFO;
    $choices['2'] = \core\output\notification::NOTIFY_WARNING;
    $choices['3'] = \core\output\notification::NOTIFY_ERROR;
    foreach ($messages as $message) {
        \core\notification::add($message->messagetext, $choices[$message->messagetype]);
        $messageManager->read_message($message->id, $USER->id);
    }
}