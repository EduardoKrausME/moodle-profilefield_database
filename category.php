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

require_once("../../../../config.php");

$context = context_system::instance();
$PAGE->set_url(new moodle_url("/user/profile/field/database/category.php"));
$PAGE->set_context($context);
$PAGE->set_title(get_string("all_category", "profilefield_database"));
$PAGE->set_heading(get_string("all_category", "profilefield_database"));

$PAGE->navbar->add(get_string("profilefields", "admin"),
    new moodle_url("/user/profile/index.php"));
$PAGE->navbar->add(get_string("all_category", "profilefield_database"),
    new moodle_url("/user/profile/field/database/category.php"));

require_login();
require_capability("profilefield/database:addinstance", $context);

echo $OUTPUT->header();

$categorys = $DB->get_records("profilefield_database_category");
if (!$categorys) {
    $data = [];
} else {
    $data = [
        "is_categorys" => count($categorys),
        "categorys" => array_values($categorys),
    ];
}
echo $OUTPUT->render_from_template("profilefield_database/category", $data);

echo $OUTPUT->footer();

