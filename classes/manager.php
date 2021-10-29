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

namespace local_message;

use dml_transaction_exception;
use stdClass;
use dml_exception;

class manager
{
    /**
     * Writes the new message to the table local_message
     * @param string text of the message
     * @param string type of the message
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
     * Adds the read message to the table local_message_read for the given user
     * @param int $messageID id of the user
     * @return bool
     */
    public function mark_read_message(int $messageID, int $userID): bool
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
     * @param $userID
     * @return array
     */
    public function get_messages($userID): array
    {
        global $DB;
        $sql = "SELECT lm.id, lm.messagetype, lm.messagetext 
            FROM {local_message} lm 
            LEFT OUTER JOIN {local_message_read} lmr 
            ON lm.id = lmr.messageid
            AND lmr.userid = :userid
            WHERE lmr.userid IS NULL";

        $params = [
            'userid' => $userID,
        ];

        try {
            return $DB->get_records_sql($sql, $params);
        } catch (dml_exception $e) {
            return array();
        }
    }

    /**
     * gets a single mesage by its id
     * @param int $messageid
     * @throws dml_exception
     */
    public function get_message(int $messageid)
    {
        global $DB;
        return $DB->get_record('local_message', ['id' => $messageid]);
    }

    /**
     * @param $id
     * @param $messagetext
     * @param $messagetype
     * @return bool
     */
    public function update_message($id, $messagetext, $messagetype): bool
    {
        global $DB;
        $object = new stdClass();
        $object->id = $id;
        $object->messagetype = $messagetype;
        $object->messagetext = $messagetext;
        try {
            return $DB->update_record('local_message', $object);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Deletes message and records for an message id
     * @param $messageid
     * @return bool
     * @throws dml_transaction_exception
     * @throws dml_exception
     */
    public function delete_message($messageid) : bool
    {
        global $DB;
        $transaction = $DB->start_delegated_transaction();
        $delete_message = $DB->delete_records('local_message', ['id' => $messageid]);
        $delete_message_read = $DB->delete_records('local_message_read', ['messageid' => $messageid]);
        if ($delete_message && $delete_message_read)
            $DB->commit_delegated_transaction($transaction);
        return true;
    }


}