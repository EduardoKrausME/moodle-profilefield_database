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
 * VO files.
 *
 * @package   profilefield_database
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace profilefield_database\vo;

/**
 * Class profilefield_database_data
 *
 * @package profilefield_database\vo
 */
class profilefield_database_data {

    /**
     * profilefield_database_data constructor.
     *
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
    }

    /** @var int */
    public $id;

    /** @var int */
    public $categoryid;

    /** @var string */
    public $data0;
    /** @var string */
    public $data1;
    /** @var string */
    public $data2;
    /** @var string */
    public $data3;
    /** @var string */
    public $data4;
    /** @var string */
    public $data5;
    /** @var string */
    public $data6;
    /** @var string */
    public $data7;
    /** @var string */
    public $data8;
    /** @var string */
    public $data9;
}
