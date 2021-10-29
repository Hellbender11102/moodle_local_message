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
 * local_message
 *
 * @package    local_message
 * @copyright  2021 SysBind Ltd. <service@sysbind.co.il>
 * @auther     schindlerl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$functions = array(
    'local_message_delete_message' => array(           //web service name (unique in all Moodle)
        'classname'   => 'local_message_external', //class containing the function implementation
        'methodname'  => 'delete_message',              //name of the function into the class
        'classpath'   => 'local/message/externallib.php',     //file containing the class (only used for core external function, not needed if your file is 'component/externallib.php'),
        'description' => 'Delete a message',
        'type' => 'write',
        'ajax' => 'true',
       'capabilities' => 'true',
    )
);