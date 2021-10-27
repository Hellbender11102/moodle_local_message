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
 * Group external PHPunit tests
 *
 * @package    local_message
 * @category   external
 * @copyright  schindlerl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use local_message\manager;
global $CFG;
require_once($CFG->dirroot . '/local/message/lib.php');

class local_message_manager_test extends advanced_testcase
{

    /**
     * Test that we create a message
     */
    public function test_create_message(){
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manager();

        $messages = $manager->get_messages(2);
        $this->assertEmpty($messages);

        $type = \core\output\notification::NOTIFY_SUCCESS;
        $text = 'Test message';

        $result = $manager->create_message($text, $type);

        $this->assertTrue($result);

        $messages = $manager->get_messages(2);
        $this->assertNotEmpty($messages);
        $this->assertCount(1, $messages);

        $message = array_pop($messages);

        $this->assertEquals($text, $message->messagetext);
        $this->assertEquals($type, $message->messagetype);
    }

    /**
     * tests gets all unread messages for the given user
     */
    public function test_get_messages(){
        global $DB;
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manager();

        $type = \core\output\notification::NOTIFY_SUCCESS;
        $text = 'Test message';

        $result = $manager->create_message($text . '1', $type);
        $result = $manager->create_message($text . '2', $type);
        $result = $manager->create_message($text . '3', $type);
        $result = $manager->create_message($text . '4', $type);

        $messages = $DB->get_records('local_message');

        foreach ($messages as $id => $message){
            $manager->mark_read_message($id,1);
        }

        $messages = $manager->get_messages(2);

        $this->assertCount(4,$messages);


        foreach ($messages as $id => $message){
            $manager->mark_read_message($id,2);
        }
        $messages = $manager->get_messages(2);

        $this->assertCount(0,$messages);
    }

}
