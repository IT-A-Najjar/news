<?php
// This file is part of Moodle Course Rollover Plugin
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
 * @package     local_report
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


use local_news\manager;

require_once(__DIR__ . '/../../config.php');
require_login();
$PAGE->set_url(new moodle_url('/local/news/report.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('REPORT NEWS');

global $DB;
$manager = new manager();

$news = $manager->get_report_news();

echo $OUTPUT->header();
$templatecontext = (object)[
    'news' => array_values($news),
    'backurl' => new moodle_url('/?redirect=0'),
    'delurl' => new moodle_url('/local/news/manage.php'),
];

echo $OUTPUT->render_from_template('local_news/reportnews', $templatecontext);

echo $OUTPUT->footer();