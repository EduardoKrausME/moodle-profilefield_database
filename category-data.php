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
 * Menu profile field definition.
 *
 * @package   profilefield_database
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use profilefield_database\vo\profilefield_database_category;
use profilefield_database\vo\profilefield_database_data;

require_once("../../../../config.php");

$id = required_param("id", PARAM_INT);

if ($id == -1) {
    $fielddata = new profilefield_database_data(-1);

    $categoryid = required_param("category", PARAM_INT);
    /** @var profilefield_database_category $category */
    $category = $DB->get_record("profilefield_database_category", ["id" => $categoryid], "*", MUST_EXIST);
} else {
    /** @var profilefield_database_data $fielddata */
    $fielddata = $DB->get_record("profilefield_database_data", ["id" => $id], "*", MUST_EXIST);

    /** @var profilefield_database_category $category */
    $category = $DB->get_record("profilefield_database_category", ["id" => $fielddata->categoryid], "*", MUST_EXIST);
}

$context = context_system::instance();
$PAGE->set_url("/user/profile/field/database/category-data.php", ["id" => $id]);
$PAGE->set_context($context);
$PAGE->set_title($category->name);
$PAGE->set_heading($category->name);

$PAGE->navbar->add(get_string("profilefields", "admin"), new moodle_url("/user/profile/index.php"));
$PAGE->navbar->add(get_string("all_category", "profilefield_database"),
    new moodle_url("/user/profile/field/database/category.php"));
$PAGE->navbar->add($category->name,
    new moodle_url("/user/profile/field/database/category-details.php", ["id" => $category->id]));
if ($id == -1) {
    $PAGE->navbar->add(get_string("new-data", "profilefield_database"));
} else {
    $PAGE->navbar->add($fielddata->data0,
        new moodle_url("/user/profile/field/database/category-data.php", ["id" => $fielddata->id]));
}

require_login();
require_capability("profilefield/database:addinstance", $context);

if (optional_param("delete", false, PARAM_TEXT)) {

    $sql = "
        SELECT u.*
          FROM {user_info_data} uid
          JOIN {user}           u   ON uid.userid = u.id
         WHERE fieldid IN(SELECT id FROM {user_info_field} WHERE datatype LIKE 'database')
           AND data LIKE :data";

    $user = $DB->get_record_sql($sql, ["data" => $fielddata->id]);
    if ($user) {
        redirect(new moodle_url("/user/profile/field/database/category-details.php", ["id" => $category->id]),
            get_string("delete-data-usedata", "profilefield_database",
                ["wwwroot" => $CFG->wwwroot, "id" => $user->id, "name" => fullname($user)]), null,
            \core\output\notification::NOTIFY_ERROR);
    }

    $option1 = optional_param("confirm", "", PARAM_TEXT) == md5("{$id}" . sesskey());
    $option2 = optional_param("confirm", "", PARAM_TEXT) == "{$id}-1";
    if ($option1 || $option2) {
        require_sesskey();

        $DB->delete_records("profilefield_database_data", ["id" => $fielddata->id]);
        redirect(new moodle_url("/user/profile/field/database/category-details.php", ["id" => $category->id]),
            get_string("delete-success", "profilefield_database"), null,
            \core\output\notification::NOTIFY_SUCCESS);
    }

    $PAGE->navbar->add(get_string("delete-data", "profilefield_database"));

    echo $OUTPUT->header();

    $cancelurl = new moodle_url("/user/profile/field/database/category-details.php", ["id" => $category->id]);

    $continueurl = new moodle_url("/user/profile/field/database/category-data.php",
        ["id" => $id, "delete" => 1, "confirm" => md5("{$id}" . sesskey()), "sesskey" => sesskey()]);
    $continuebutton = new single_button($continueurl, get_string("delete"), "post", single_button::BUTTON_DANGER);
    echo $OUTPUT->confirm(
        get_string("confirm-delete-data", "profilefield_database", ["name" => $category->field0, "value" => $fielddata->data0]),
        $continuebutton,
        $cancelurl);

    echo $OUTPUT->footer();
    die;
}

if (optional_param("data_id", false, PARAM_INT)) {
    require_sesskey();

    for ($i = 0; $i <= 9; $i++) {
        if (isset($category->{"field{$i}"}[3])) {
            if ($category->{"type{$i}"} == "int") {
                $fielddata->{"data{$i}"} = required_param("data{$i}", PARAM_INT);
            } else if ($category->{"type{$i}"} == "text") {
                $fielddata->{"data{$i}"} = required_param("data{$i}", PARAM_TEXT);
            }
        }
    }
    if ($id == -1) {
        $fielddata->categoryid = $category->id;
        $fielddata->id = $DB->insert_record("profilefield_database_data", $fielddata);
    } else {
        $DB->update_record("profilefield_database_data", $fielddata);
    }

    redirect(new moodle_url("/user/profile/field/database/category-details.php?id=", ["id" => $category->id]));
}

echo $OUTPUT->header();

$data = [
    "category" => $category,
    "data" => $fielddata,
    "datas" => [
        ["id" => 0, "data" => $fielddata->data0, "field" => $category->field0, "type" => $category->type0],
        ["id" => 1, "data" => $fielddata->data1, "field" => $category->field1, "type" => $category->type1],
        ["id" => 2, "data" => $fielddata->data2, "field" => $category->field2, "type" => $category->type2],
        ["id" => 3, "data" => $fielddata->data3, "field" => $category->field3, "type" => $category->type3],
        ["id" => 4, "data" => $fielddata->data4, "field" => $category->field4, "type" => $category->type4],
        ["id" => 5, "data" => $fielddata->data5, "field" => $category->field5, "type" => $category->type5],
        ["id" => 6, "data" => $fielddata->data6, "field" => $category->field6, "type" => $category->type6],
        ["id" => 7, "data" => $fielddata->data7, "field" => $category->field7, "type" => $category->type7],
        ["id" => 8, "data" => $fielddata->data8, "field" => $category->field8, "type" => $category->type8],
        ["id" => 9, "data" => $fielddata->data9, "field" => $category->field9, "type" => $category->type9],
    ],
];
echo $OUTPUT->render_from_template("profilefield_database/category-data", $data);

echo $OUTPUT->footer();

