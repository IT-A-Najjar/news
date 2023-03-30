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

namespace local_news\form;

use moodleform;

require_once("$CFG->libdir/formslib.php");
defined('MOODLE_INTERNAL') || die;
class add extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
        global $DB;
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('text', 'newstitle', '<h3>'.get_string('newstitle','local_news').'</h3>'); // Add elements to your form
        $mform->setType('newstitle', PARAM_NOTAGS);  //Set type of element
        $mform->addRule('newstitle', 'Mandatory', 'required', null, 'client');
        $mform->setDefault('newstitle', get_string('entertitle','local_news'));        //Default value

        $records=$DB->get_records('local_news_categories');
        $categories=array();
        foreach($records as $record)
        {
            $categories[$record->id]=$record->categoryname;
        }
        $mform->addElement('select', 'newstype','<h3>'.get_string('newstype','local_news').'</h3>',$categories);
        $mform->addRule('newstype', 'Mandatory', 'required', null, 'client');
        $mform->setDefault('newstype', '1');


        $mform->addElement('textarea', 'newstext', '<h3>'.get_string('content','local_news').'</h3>', 'wrap="virtual" rows="5" cols="5"', array('maxlength' => '700'));
        $mform->setType('newstext', PARAM_RAW);
        $mform->addRule('newstext', get_string('required', 'local_news'), 'required', null, 'client');
        $mform->setDefault('newstext', get_string('entercontent','local_news'));

        $userpicoptions = array('subdirs' => 0, 'maxbytes' => '', 'context' => $context,
            'accepted_types' => array('.png', '.jpeg'));
        $mform->addElement('filepicker', 'image', '<h3>'.get_string('newsimage','local_news').'</h3>', null,
            array('maxbytes' => 11111111111111, 'accepted_types' => '*'));

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
