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
 * @package    local_message
 * @copyright  2021 SysBind Ltd. <service@sysbind.co.il>
 * @auther     schindlerl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_message\manager;

require_once($CFG->dirroot . '/local/message/classes/manager.php');

function local_message_before_footer()
{
    if(!get_config('local_message', 'enabled'))
        return;

    global $USER;

    $manager = new manager();

    $messages = $manager->get_messages($USER->id);

    $choices = array();
    $choices['0'] = \core\output\notification::NOTIFY_SUCCESS;
    $choices['1'] = \core\output\notification::NOTIFY_INFO;
    $choices['2'] = \core\output\notification::NOTIFY_WARNING;
    $choices['3'] = \core\output\notification::NOTIFY_ERROR;
    foreach ($messages as $message) {
        \core\notification::add($message->messagetext, $choices[$message->messagetype]);
        $manager->mark_read_message($message->id, $USER->id);
    }
}