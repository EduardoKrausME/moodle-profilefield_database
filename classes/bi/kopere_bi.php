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
 * Kopere BI file.
 *
 * @package   profilefield_database
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace profilefield_database\bi;

use local_kopere_bi\vo\local_kopere_bi_block;
use local_kopere_bi\vo\local_kopere_bi_cat;
use local_kopere_bi\vo\local_kopere_bi_page;
use profilefield_database\vo\profilefield_database_cat;

/**
 * Class kopere_bi
 *
 * @package profilefield_database\bi
 */
class kopere_bi {

    /**
     * Var bicat
     *
     * @var local_kopere_bi_cat
     */
    public $bicat;

    /**
     * kopere_bi constructor.
     *
     * @throws \dml_exception
     */
    public function __construct() {
        global $DB;

        $this->bicat = $DB->get_record_select("local_kopere_bi_cat",
            "refkey = 'students'");
        if (!$this->bicat) {
            $this->bicat = $DB->get_record_select("local_kopere_bi_cat",
                "title = 'lang::report_1_cat_title::local_kopere_bi'");
        }
        if (!$this->bicat) {
            $this->bicat = $DB->get_record_select("local_kopere_bi_cat",
                "description = 'lang::report_1_cat_description::local_kopere_bi'");
        }
        if (!$this->bicat) {
            $this->bicat = (object)[
                "refkey" => "online",
                "title" => "lang::report_1_cat_title::local_kopere_bi",
                "description" => "lang::report_1_cat_description::local_kopere_bi",
                "sortorder" => null,
            ];
            $this->bicat->id = $DB->insert_record("local_kopere_bi_cat", $this->bicat);
        }
    }

    /** @var profilefield_database_cat */
    public $category;

    /** @var local_kopere_bi_page $page */
    public $page;

    /** @var local_kopere_bi_block $block */
    public $block;

    /**
     * Function set_category
     *
     * @param profilefield_database_cat $category
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function set_category($category) {
        global $USER, $DB;

        $this->category = $category;

        $this->page = $DB->get_record("local_kopere_bi_page", ["refkey" => "profilefield_database_page_{$this->category->id}"]);
        if (!$this->page) {
            $this->page = (object)[
                "refkey" => "profilefield_database_page_{$this->category->id}",
                "cat_id" => $this->bicat->id,
                "title" => get_string("report_category_title", "profilefield_database", $this->category->name),
                "description" => get_string("report_category_description", "profilefield_database", $this->category->name),
                "user_id" => $USER->id,
                "sortorder" => null,
                "time" => time(),
            ];
            $this->page->id = $DB->insert_record("local_kopere_bi_page", $this->page);
        }

        $this->block = $DB->get_record("local_kopere_bi_block", ["refkey" => "profilefield_database_block_{$this->category->id}"]);
        if (!$this->block) {
            $this->block = (object)[
                "refkey" => "profilefield_database_block_{$this->category->id}",
                "page_id" => $this->page->id,
                "type" => "block-1",
                "sequence" => 1,
                "time" => time(),
            ];
            $this->block->id = $DB->insert_record("local_kopere_bi_block", $this->block);
        }
    }

    /**
     * Function delete_category
     *
     * @param profilefield_database_cat $category
     *
     * @throws \dml_exception
     */
    public static function delete_category($category) {
        global $DB;

        $DB->delete_records("local_kopere_bi_page", ["refkey" => "profilefield_database_page_{$category->id}"]);
        $DB->delete_records("local_kopere_bi_block", ["refkey" => "profilefield_database_block_{$category->id}"]);
        $DB->delete_records("local_kopere_bi_element", ["refkey" => "profilefield_database_element_{$category->id}"]);
    }

    /**
     * Function create_report
     *
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public function create_report() {
        global $DB;

        $info = (object)[
            "column" => [
                "u_id" => (object)[
                    "key" => "u_id",
                    "title" => "#",
                    "type" => "userphotoRenderer",
                    "mustache" => "",
                ],
                "firstname" => (object)[
                    "key" => "firstname",
                    "title" => "lang::u_fullname::local_kopere_bi",
                    "type" => "userfullname",
                    "mustache" =>
                        '<a href="{{{config.wwwroot}}}/user/view.php?id={{{u_id}}}" target="profile">{{{u_fullname}}}</a>',
                ],
                "lastname" => (object)[
                    "key" => "lastname",
                    "title" => "",
                    "type" => "none",
                    "mustache" => "",
                ],
                "email" => (object)[
                    "key" => "email",
                    "title" => "lang::email::moodle",
                    "type" => "string",
                    "mustache" => '<a href="mailto:{{{email}}}" target="_blank">{{{email}}}</a>',
                ],
                "city" => (object)[
                    "key" => "city",
                    "title" => "lang::city::moodle",
                    "type" => "none",
                    "mustache" => "{{{city}}}",
                ],
                "country" => (object)[
                    "key" => "country",
                    "title" => "lang::country::moodle",
                    "type" => "none",
                    "mustache" => "",
                ],
                "firstaccess" => (object)[
                    "key" => "firstaccess",
                    "title" => "lang::firstaccess::moodle",
                    "type" => "none",
                    "mustache" => "{{{firstaccess}}}",
                ],
                "lastaccess" => (object)[
                    "key" => "lastaccess",
                    "title" => "lang::lastaccess::moodle",
                    "type" => "none",
                    "mustache" => "{{{lastaccess}}}",
                ],
                "lastlogin" => (object)[
                    "key" => "lastlogin",
                    "title" => "lang::lastlogin::moodle",
                    "type" => "none",
                    "mustache" => "{{{lastlogin}}}",
                ],
                "timecreated" => (object)[
                    "key" => "timecreated",
                    "title" => "lang::timecreated::moodle",
                    "type" => "none",
                    "mustache" => "",
                ],
                "id" => (object)[
                    "key" => "id",
                    "title" => "",
                    "type" => "none",
                    "mustache" => "",
                ],
                "categoryid" => (object)[
                    "key" => "categoryid",
                    "title" => "",
                    "type" => "none",
                    "mustache" => "",
                ],
                "data0" => (object)[
                    "key" => "data0",
                    "title" => $this->category->field0,
                    "type" => $this->get_type(0),
                    "mustache" => "{{{data0}}}",
                ],
                "data1" => (object)[
                    "key" => "data1",
                    "title" => $this->category->field1,
                    "type" => $this->get_type(1),
                    "mustache" => "{{{data1}}}",
                ],
                "data2" => (object)[
                    "key" => "data2",
                    "title" => $this->category->field2,
                    "type" => $this->get_type(2),
                    "mustache" => "{{{data2}}}",
                ],
                "data3" => (object)[
                    "key" => "data3",
                    "title" => $this->category->field3,
                    "type" => $this->get_type(3),
                    "mustache" => "{{{data3}}}",
                ],
                "data4" => (object)[
                    "key" => "data4",
                    "title" => $this->category->field4,
                    "type" => $this->get_type(4),
                    "mustache" => "{{{data4}}}",
                ],
                "data5" => (object)[
                    "key" => "data5",
                    "title" => $this->category->field5,
                    "type" => $this->get_type(5),
                    "mustache" => "{{{data5}}}",
                ],
                "data6" => (object)[
                    "key" => "data6",
                    "title" => $this->category->field6,
                    "type" => $this->get_type(6),
                    "mustache" => "{{{data6}}}",
                ],
                "data7" => (object)[
                    "key" => "data7",
                    "title" => $this->category->field7,
                    "type" => $this->get_type(7),
                    "mustache" => "{{{data7}}}",
                ],
                "data8" => (object)[
                    "key" => "data8",
                    "title" => $this->category->field8,
                    "type" => $this->get_type(8),
                    "mustache" => "{{{data8}}}",
                ],
                "data9" => (object)[
                    "key" => "data9",
                    "title" => $this->category->field9,
                    "type" => $this->get_type(9),
                    "mustache" => "{{{data9}}}",
                ],
            ],
        ];

        $element = $DB->get_record("local_kopere_bi_element", ["refkey" => "profilefield_database_element_{$this->category->id}"]);
        if (!$element) {
            $element = (object)[
                "refkey" => "profilefield_database_element_{$this->category->id}",
                "title" => get_string("report_category_title", "profilefield_database", $this->category->name),
                "block_id" => $this->block->id,
                "block_num" => 1,
                "type" => "table",
                "theme" => "light",
                "css" => "",
                "html_before" => "",
                "html_after" => "",
                "cache" => "6h",
                "reload" => "2h",
                "time" => time(),
                "commandsql" => "SELECT u.id AS u_id,
       u.firstname,
       u.lastname,
       u.email,
       u.city,
       u.country,
       u.firstaccess,
       u.lastaccess,
       u.lastlogin,
       u.timecreated,
       pdd.*
  FROM mdl_user_info_data             uid
  JOIN mdl_user                       u   ON u.id = uid.userid
  JOIN mdl_profilefield_database_data pdd ON pdd.id = uid.data
 WHERE uid.fieldid IN(
            SELECT id FROM mdl_user_info_field WHERE param1 = {$this->category->id}
        )",
                "info" => json_encode($info, JSON_PRETTY_PRINT),
            ];
            $DB->insert_record("local_kopere_bi_element", $element);
        } else {
            $element->info = json_encode($info, JSON_PRETTY_PRINT);
            $DB->update_record("local_kopere_bi_element", $element);
        }
    }

    /**
     * Function get_type
     *
     * @param int $num
     *
     * @return string
     */
    private function get_type($num) {
        $field = $this->category->{"field{$num}"};
        if (!isset($field[3])) {
            return "none";
        }

        switch ($this->category->{"type{$num}"}) {
            case "int":
                return "int";
            case "text":
                return "string";
            default:
                return "string";
        }
    }
}
