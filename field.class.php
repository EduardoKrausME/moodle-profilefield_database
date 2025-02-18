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
 * UserFieldDB profile field.
 *
 * @package   profilefield_database
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use profilefield_database\vo\profilefield_database_cat;
use profilefield_database\vo\profilefield_database_data;

/**
 * Class profile_field_database
 *
 * @package   profilefield_database
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class profile_field_database extends profile_field_base {

    /** @var array $options */
    public $options;

    /** @var int $datakey */
    public $datakey;

    /**
     * Constructor method.
     *
     * Pulls out the options for the menu from the database and sets the the corresponding key for the data if it
     * exists.
     *
     * @param int $fieldid
     * @param int $userid
     * @param object $fielddata
     *
     * @throws dml_exception
     */
    public function __construct($fieldid = 0, $userid = 0, $fielddata = null) {
        global $DB;

        // First call parent constructor.
        parent::__construct($fieldid, $userid, $fielddata);

        // Param 1 for menu type is the options.
        if (isset($this->field->param1)) {

            $fielddatas = $DB->get_records("profilefield_database_data",
                ["categoryid" => $this->field->param1], "data0 ASC", "id,data0");
            /** @var profilefield_database_data $fielddata */
            foreach ($fielddatas as $fielddata) {
                $this->options[$fielddata->id] = $fielddata->data0;
            }
        } else {
            $this->options = [];
        }

        $this->datakey = $this->data;
    }

    /**
     * Create the code snippet for this field instance
     * Overwrites the base class method
     *
     * @param MoodleQuickForm $mform Moodle form instance
     *
     * @throws Exception
     */
    public function edit_field_add($mform) {
        $mform->addElement("select", $this->inputname, format_string($this->field->name), $this->options);

        if (has_capability("moodle/site:config", context_system::instance())) {
            global $CFG;
            $url = "{$CFG->wwwroot}/user/profile/field/database/category-details.php?id={$this->field->param1}";
            $mform->addElement("static", "{$this->inputname}_static", "",
                "<a href='{$url}'>" . get_string("edit_values_fields", "profilefield_database") . "</a>");
        }
    }

    /**
     * Set the default value for this field instance
     * Overwrites the base class method.
     *
     * @param MoodleQuickForm $mform Moodle form instance
     */
    public function edit_field_set_default($mform) {
        $key = $this->field->defaultdata;
        if (isset($this->options[$key]) || ($key = array_search($key, $this->options)) !== false) {
            $defaultkey = $key;
        } else {
            $defaultkey = "";
        }
        $mform->setDefault($this->inputname, $defaultkey);
    }

    /**
     * The data from the form returns the key.
     *
     * This should be converted to the respective option string to be saved in database
     * Overwrites base class accessor method.
     *
     * @param mixed $data          The key returned from the select input in the form
     * @param stdClass $datarecord The object that will be used to save the record
     *
     * @return mixed Data or null
     */
    public function edit_save_data_preprocess($data, $datarecord) {
        return isset($this->options[$data]) ? $data : null;
    }

    /**
     * When passing the user object to the form class for the edit profile page
     * we should load the key for the saved data
     *
     * Overwrites the base class method.
     *
     * @param stdClass $user User object.
     */
    public function edit_load_user_data($user) {
        $user->{$this->inputname} = $this->datakey;
    }

    /**
     * HardFreeze the field if locked.
     *
     * @param MoodleQuickForm $mform instance of the moodleform class
     *
     * @throws coding_exception
     * @throws dml_exception
     */
    public function edit_field_set_locked($mform) {
        if (!$mform->elementExists($this->inputname)) {
            return;
        }
        if ($this->is_locked() && !has_capability("moodle/user:update", context_system::instance())) {
            $mform->hardFreeze($this->inputname);
            $mform->setConstant($this->inputname, format_string($this->datakey));
        }
    }

    /**
     * Convert external data (csv file) from value to key for processing later by edit_save_data_preprocess
     *
     * @param string $value one of the values in menu options.
     *
     * @return int options key for the menu
     */
    public function convert_external_data($value) {
        if (isset($this->options[$value])) {
            $retval = $value;
        } else {
            $retval = array_search($value, $this->options);
        }

        // If value is not found in options then return null, so that it can be handled
        // later by edit_save_data_preprocess.
        if ($retval === false) {
            $retval = null;
        }
        return $retval;
    }

    /**
     * Return the field type and null properties.
     * This will be used for validating the data submitted by a user.
     *
     * @return array the param type and null property
     * @since Moodle 3.2
     */
    public function get_field_properties() {
        return [PARAM_TEXT, NULL_NOT_ALLOWED];
    }

    /**
     * Return the field settings suitable to be exported via an external function.
     *
     * @return array all the settings
     */
    public function get_field_config_for_external() {

        if (isset($this->field->param1) && !empty($this->options)) {
            // Remove "Choose" option to make it consisten with the rest of the data.
            if (!empty($this->field->required)) {
                unset($this->options[""]);
            }
        }

        return (array)$this->field;
    }

    /**
     * Display the data for this field
     *
     * @return string HTML.
     * @throws dml_exception
     */
    public function display_data() {
        global $DB, $OUTPUT;

        /** @var profilefield_database_data $fielddata */
        $fielddata = $DB->get_record("profilefield_database_data", ["id" => $this->data]);
        /** @var profilefield_database_cat $category */
        $category = $DB->get_record("profilefield_database_cat", ["id" => $fielddata->categoryid]);

        $data = [
            "fields" => [
                ["visible" => isset($fielddata->data0[3]), "data" => $fielddata->data0, "name" => $category->field0],
                ["visible" => isset($fielddata->data1[3]), "data" => $fielddata->data1, "name" => $category->field1],
                ["visible" => isset($fielddata->data2[3]), "data" => $fielddata->data2, "name" => $category->field2],
                ["visible" => isset($fielddata->data3[3]), "data" => $fielddata->data3, "name" => $category->field3],
                ["visible" => isset($fielddata->data4[3]), "data" => $fielddata->data4, "name" => $category->field4],
                ["visible" => isset($fielddata->data5[3]), "data" => $fielddata->data5, "name" => $category->field5],
                ["visible" => isset($fielddata->data6[3]), "data" => $fielddata->data6, "name" => $category->field6],
                ["visible" => isset($fielddata->data7[3]), "data" => $fielddata->data7, "name" => $category->field7],
                ["visible" => isset($fielddata->data8[3]), "data" => $fielddata->data8, "name" => $category->field8],
                ["visible" => isset($fielddata->data9[3]), "data" => $fielddata->data9, "name" => $category->field9],
            ],
        ];
        return $OUTPUT->render_from_template("profilefield_database/data-display", $data);
    }
}
