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

global $DB;

//require_login();
//$context = context_system::instance();
//require_capability('local/news:managenews', $context);

$PAGE->set_url(new moodle_url('/local/news/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('News');
$PAGE->set_heading('Manage News');
$PAGE->requires->js_call_amd('local_news/confirm');
$PAGE->requires->css('/local/news/styles.css');

$news=$DB->get_records('local_news',null,'id');
$delid=optional_param('delid',null,PARAM_INT);
$manager=new manager();
if($delid){
    $manager->delete_news($delid);
    redirect($CFG->wwwroot.'/local/news/manage.php','You created a news with title '.$fromform->newstitle);
}
echo $OUTPUT->header();
$templatecontext = (object)[
    'news' => array_values($news),
    'editurl' => new moodle_url('/local/news/add.php'),
    'delurl' => new moodle_url('/local/news/manage.php'),
    'curl' => new moodle_url('/local/news/addcategory.php'),
    'bulkediturl' => new moodle_url('/local/news/bulkadd.php'),
];

echo $OUTPUT->render_from_template('local_news/manage', $templatecontext);

echo $OUTPUT->footer();
