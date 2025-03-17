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

use profilefield_database\vo\profilefield_database_cat;

/**
 * Class profile_define_database
 *
 * @package   profilefield_database
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class profile_define_database extends profile_define_base {
    /**
     * Function define_form_specific
     *
     * @param MoodleQuickForm $mform
     *
     * @throws coding_exception
     * @throws dml_exception
     */
    public function define_form_specific($mform) {
        global $DB, $CFG;

        // Associated db field.
        $dbs = [];
        $categorys = $DB->get_records("profilefield_database_cat", [], "name ASC", "id,name");
        /** @var profilefield_database_cat $category */
        foreach ($categorys as $category) {
            $dbs[$category->id] = $category->name;
        }

        $mform->addElement('html',
            '<div class="alert alert-warning">' . get_string("manage-category-link", "profilefield_database", $CFG->wwwroot) . '</div>');

        $mform->addElement("select", "param1", get_string("select-category", "profilefield_database"), $dbs);
        $mform->setType('param1', PARAM_INT);
        $mform->addRule('param1', null, 'required', null, 'client');
    }

    /**
     * Function define_validate_specific
     *
     * @param array|object $data
     * @param array $files
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public function define_validate_specific($data, $files) {
        global $DB, $CFG;

        $data = (object)$data;

        $errors = [];
        if (!empty($data->param1)) {
            $category = $DB->get_record("profilefield_database_cat", ["id" => $data->param1], "id");
            if (!$category) {
                $errors["param1"] = get_string("missing-value-link", "profilefield_database", $CFG->wwwroot);
            }
        }
        return $errors;
    }
}
