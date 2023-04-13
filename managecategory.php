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
 * @package     local_news
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_news\manager;

require_once(__DIR__ . '/../../config.php');
require_login();
$context = context_system::instance();
require_capability('local/news:managenews',$context);

global $DB;

$PAGE->set_url(new moodle_url('/local/news/managecategory.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('title_manage_category','local_news'));
$PAGE->set_heading(get_string('heading_manage_category','local_news'));
$PAGE->requires->js_call_amd('local_message/confirm');
$PAGE->requires->css('/local/news/styles.css');

$category=$DB->get_records('local_news_categories',null,'id');
$delid=optional_param('delid',null,PARAM_INT);
$manager=new manager();
if($delid){
    $manager->delete_category($delid);
    redirect($CFG->wwwroot.'/local/news/managecategory.php',get_string('created_form_category','local_news').$fromform->newstitle);
}
echo $OUTPUT->header();
$templatecontext = (object)[
    'category' => array_values($category),
    'editurl' => new moodle_url('/local/news/addcategory.php'),
    'delurl' => new moodle_url('/local/news/managecategory.php'),
];

echo $OUTPUT->render_from_template('local_news/managecategory', $templatecontext);

echo $OUTPUT->footer();
