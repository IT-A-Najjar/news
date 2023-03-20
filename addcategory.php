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
 * @package     local_message
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


use local_news\form\addcategory;
use local_news\manager;

require_once(__DIR__ . '/../../config.php');
require_login();

$PAGE->set_url(new moodle_url('/local/news/addcategory.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Add new Category');

$categoryid=optional_param('categoryid',null,PARAM_INT);
$mform = new addcategory();
if($mform->is_cancelled()){
    redirect($CFG->wwwroot.'/local/news/managecategory.php',get_string('cancelled_form','local_news'));

}elseif ($fromform= $mform->get_data()){
    $manager = new manager();

    if ($fromform->id) {
        // We are updating an existing message.
        $manager->update_category($fromform->id,$fromform->categoryname);
        redirect($CFG->wwwroot . '/local/news/managecategory.php', get_string('updated_form', 'local_message') . $fromform->messagetest);
    }

    $manager->create_category($fromform->categoryname);

    //go back to manage.php
    redirect($CFG->wwwroot.'/local/news/managecategory.php','You created a new category  '.$fromform->categoryname);
}
if($categoryid){
    global $DB;
    $manager=new manager();
    $category = $manager->get_category($categoryid);
    if (!$category) {
        throw new invalid_parameter_exception('news not found');
    }
    $mform->set_data($category);
}

echo $OUTPUT->header();
echo '<h1 class="aa">ADD NEW SCATEGORY</h1>';
$mform->display();
echo $OUTPUT->footer();
