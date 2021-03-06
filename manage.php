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

global $CFG;
global $DB;

require_login();
$context = context_system::instance();
require_capability('local/message:managemessages', $context);

$PAGE->set_url(new moodle_url('/local/message/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('titlemanage','local_message'));


$PAGE->requires->js_call_amd('local_message/confirm');

$messages = $DB->get_records('local_message');

$templatecontext = (object)[
    'messages' => array_values($messages),
    'creaturl' => new moodle_url('/local/message/edit.php'),
    'editurl' => new moodle_url('/local/message/edit.php'),
    'btnEdit' => get_string('btnEdit','local_message'),
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_message/manage', $templatecontext);

echo $OUTPUT->footer();
