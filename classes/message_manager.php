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
use core\output\notification;

/**
 *  local_message
 *
 * @package    local_message
 * @copyright  2021 SysBind Ltd. <service@sysbind.co.il>
 * @auther     schindlerl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class message_manager
{
    /**
     * Writes the new message to the table local_message
     * @param string
     * @param string
     * @return bool true if successful
     */
    public function create_message(string $messageText, string $messageType): bool
    {
        global $DB;
        $recordToInsert = new stdClass();
        $recordToInsert->messagetext = $messageText;
        $recordToInsert->messagetype = $messageType;
        try {
            return $DB->insert_record('local_message', $recordToInsert, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Adds the read message to the table local_message_read
     * @param int $messageID
     * @return bool
     */
    public function read_message(int $messageID, int $userID): bool
    {
        global $DB;
        $readrecord = new stdClass();
        $readrecord->messageid = $messageID;
        $readrecord->userid = $userID;
        $readrecord->timeread = time();
        try {
            return $DB->insert_record('local_message_read', $readrecord, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * gets all unread messages for the given user
     * @param int $userID
     * @return array
     */
    public function get_message(int $userID): array
    {
        global $DB;
        $sql = "SELECT lm.id, lm.messagetype, lm.messagetext 
            FROM {local_message} lm 
            LEFT OUTER JOIN {local_message_read} lmr 
            ON lm.id = lmr.messageid
            WHERE lmr.userid != :userid 
            OR lmr.userid IS NULL";

        $params = [
            'userid' => $userID,
        ];

        try {
            return $DB->get_records_sql($sql, $params);
        } catch (dml_exception $e) {
            return array();
        }
    }

}