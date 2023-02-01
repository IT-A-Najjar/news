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
use local_news\manager;
use moodleform;
use stdClass;

require_once("$CFG->libdir/formslib.php");
class addcategory extends moodleform
{

    public function definition()
    {
        global $CFG;
        global $DB;
//        $manager=new manager();
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('text', 'categoryname', '<h3>Category Name</h3>'); // Add elements to your form
        $mform->setType('categoryname', PARAM_NOTAGS);  //Set type of element
        $mform->addRule('categoryname', 'Mandatory', 'required', null, 'client');
        $mform->setDefault('categoryname', "Enter the Category Name");        //Default value


//        $choices = array();
//        $choices['0'] = "عاجل";
//        $choices['1'] = "هام";
//        $choices['2'] = "متوسط";
//        $choices['3'] = "غير مهم";
//        $mform->addElement('select', 'categoryparent','<h3>Category Parent</h3>',$choices );
//        $mform->addRule('categoryparent', 'Mandatory', 'required', null, 'client');
//        $mform->setDefault('categoryparent', '3');

        $this->add_action_buttons();
    }
    function validation($data, $files) {
        return array();
    }
}
