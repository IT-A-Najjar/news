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

use local_news\form\add;
use local_news\manager;
require_once(__DIR__ . '/../../config.php');
require_login();

$PAGE->set_url(new moodle_url('/local/news/add.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Add new News');
$PAGE->requires->css('/local/news/styles.css');
$newsid=optional_param('newsid',null,PARAM_INT);

$mform=new add();
if($mform->is_cancelled()){
    redirect($CFG->wwwroot.'/local/news/manage.php',get_string('cancelled_form','local_news'));

}elseif ($fromform= $mform->get_data()){
    $manager = new manager();

    if ($fromform->id) {
        // We are updating an existing message.
        $manager->update_news($fromform->id, $fromform->newstitle, $fromform->newstext,$fromform->newstype,$mform->get_new_filename('image'));
        redirect($CFG->wwwroot . '/local/news/manage.php', get_string('updated_form', 'local_message') . $fromform->messagetest);
    }
    $file = $mform->get_new_filename('image');
    $fullpath = "upload/". time().$file;
    $success = $mform->save_file('image', $fullpath, true);
    if(!$success){
        echo "Oops! something went wrong!";
    }
    $manager->create_news($fromform->newstitle,$fromform->newstext,$fromform->newstype,$file);

        //go back to manage.php
    redirect($CFG->wwwroot.'/local/news/manage.php','You created a news with title '.$fromform->newstitle);
}

if($newsid){
    global $DB;
    $manager=new manager();
    $news = $manager->get_news($newsid);
    if (!$news) {
        throw new invalid_parameter_exception('news not found');
    }
    $mform->set_data($news);
}

echo $OUTPUT->header();
echo '<h1 class="aa">ADD NEW NEWS</h1>';
$mform->display();
echo $OUTPUT->footer();
