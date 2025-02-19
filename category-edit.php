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

use profilefield_database\bi\kopere_bi;
use profilefield_database\vo\profilefield_database_cat;

require_once("../../../../config.php");

$id = required_param("id", PARAM_INT);

if ($id == -1) {
    $category = new profilefield_database_cat();
} else {
    $category = $DB->get_record("profilefield_database_cat", ["id" => $id], "*", MUST_EXIST);
}

$context = context_system::instance();
$PAGE->set_url("/user/profile/field/database/category-edit.php", ["id" => $id]);
$PAGE->set_context($context);
if ($id == -1) {
    $PAGE->set_title(get_string("new-category", "profilefield_database"));
    $PAGE->set_heading(get_string("new-category", "profilefield_database"));
} else {
    $PAGE->set_title($category->name);
    $PAGE->set_heading($category->name);
}

$PAGE->navbar->add(get_string("profilefields", "admin"),
    new moodle_url("/user/profile/index.php"));
$PAGE->navbar->add(get_string("all_category", "profilefield_database"),
    new moodle_url("/user/profile/field/database/category.php"));
if ($id == -1) {
    $PAGE->navbar->add(get_string("new-category", "profilefield_database"),
        new moodle_url("/user/profile/field/database/category-details.php", ["id" => $category->id]));
} else {
    $PAGE->navbar->add($category->name,
        new moodle_url("/user/profile/field/database/category-details.php", ["id" => $category->id]));
}

require_login();
require_capability("profilefield/database:addinstance", $context);

if (optional_param("delete", false, PARAM_TEXT)) {

    $PAGE->navbar->add(get_string("delete-category", "profilefield_database"),
        new moodle_url("/user/profile/field/database/category-details.php", ["id" => $category->id]));

    $userinfofield = $DB->get_record_select("user_info_field",
        "datatype LIKE :datatype AND param1 LIKE :param1",
        ["datatype" => "database", "param1" => $category->id]);
    if ($userinfofield) {
        redirect(new moodle_url("/user/profile/field/database/category.php"),
            get_string("delete-category-usedata", "profilefield_database",
                ["wwwroot" => $CFG->wwwroot, "name" => $userinfofield->name]), null,
            \core\output\notification::NOTIFY_ERROR);
    }

    if (optional_param("confirm", "", PARAM_TEXT) == md5("{$id}" . sesskey())) {
        require_sesskey();

        $DB->delete_records("profilefield_database_data", ["categoryid" => $category->id]);

        kopere_bi::delete_category($category);

        $DB->delete_records("profilefield_database_cat", ["id" => $category->id]);
        redirect(new moodle_url("/user/profile/field/database/category.php"),
            get_string("delete-success", "profilefield_database"), null,
            \core\output\notification::NOTIFY_SUCCESS);
    }

    echo $OUTPUT->header();

    $cancelurl = new moodle_url("/user/profile/field/database/category.php");

    $continueurl = new moodle_url("/user/profile/field/database/category-edit.php",
        ["id" => $id, "delete" => 1, "confirm" => md5("{$id}" . sesskey()), "sesskey" => sesskey()]);
    $continuebutton = new single_button($continueurl, get_string("delete"), "post", "danger");
    echo $OUTPUT->confirm(
        get_string("confirm-delete-category", "profilefield_database", $category->name),
        $continuebutton,
        $cancelurl);

    echo $OUTPUT->footer();
    die;
}

if (optional_param("category_name", false, PARAM_TEXT)) {
    require_sesskey();

    $category->name = required_param("category_name", PARAM_TEXT);
    for ($i = 0; $i <= 9; $i++) {
        $category->{"field{$i}"} = required_param("field{$i}", PARAM_TEXT);
        $category->{"type{$i}"} = required_param("type{$i}", PARAM_TEXT);
    }

    $bi = new kopere_bi();

    if (isset($category->field0[2]) && isset($category->field1[2])) {
        if ($id == -1) {
            $category->id = $DB->insert_record("profilefield_database_cat", $category);

            $bi->set_category($category);
            $bi->create_report();
        } else {
            $DB->update_record("profilefield_database_cat", $category);

            $bi->set_category($category);
            $bi->create_report();
        }

        redirect(new moodle_url("/user/profile/field/database/category.php"));
    } else {
        \core\notification::add(get_string("category-save-error", "profilefield_database"),
            \core\output\notification::NOTIFY_ERROR);
    }
}

echo $OUTPUT->header();

$data = [
    "category" => $category,
    "is_add" => $id > 0,
    "fields" => [
        ["id" => 0, "field" => $category->field0, "type" => $category->type0],
        ["id" => 1, "field" => $category->field1, "type" => $category->type1],
        ["id" => 2, "field" => $category->field2, "type" => $category->type2],
        ["id" => 3, "field" => $category->field3, "type" => $category->type3],
        ["id" => 4, "field" => $category->field4, "type" => $category->type4],
        ["id" => 5, "field" => $category->field5, "type" => $category->type5],
        ["id" => 6, "field" => $category->field6, "type" => $category->type6],
        ["id" => 7, "field" => $category->field7, "type" => $category->type7],
        ["id" => 8, "field" => $category->field8, "type" => $category->type8],
        ["id" => 9, "field" => $category->field9, "type" => $category->type9],
    ],
];
echo $OUTPUT->render_from_template("profilefield_database/category-edit", $data);

echo $OUTPUT->footer();
