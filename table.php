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
 * @package     local_table
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_news\manager;

require_once(__DIR__ . '/../../config.php');
require_login();
$PAGE->set_url(new moodle_url('/local/news/table.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('TABLE NEWS');

global $DB;
$manage=new manager();
$filterid=optional_param('filter',null,PARAM_INT);
if($filterid){
    $news=$manage->get_news_filter($filterid);
}else {
    $news = $DB->get_records('local_news');
}
$category=$DB->get_records('local_news_categories');
echo $OUTPUT->header();
$templatecontext = (object)[
    'news' => array_values($news),
    'category' => array_values($category),
    'backurl' => new moodle_url('/?redirect=0'),
    'delurl' => new moodle_url('/local/news/manage.php'),
    'filter' => new moodle_url('/local/news/table.php'),
    'curl' => new moodle_url('/local/news/addcategory.php'),
];

echo $OUTPUT->render_from_template('local_news/tablenews', $templatecontext);

echo $OUTPUT->footer();
