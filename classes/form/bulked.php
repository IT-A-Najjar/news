<?php

use local_news\manager;
use moodleform;

require_once("$CFG->libdir/formslib.php");

class bulked extends moodleform{
    public function definition()
    {
        $mform = $this->_form; // Don't forget the underscore!

        // Display the list of messages with a checkbox.
        $manager = new manager();
        $news = $manager->get_all_news();

        $messagegroup = [];
        foreach ($messages as $message) {
            $messagegroup[] = $mform->createElement('advcheckbox', 'messageid' . $message->id, $message->messagetest);
        }
        $mform->addGroup($messagegroup, 'messages', get_string('choose_messages', 'local_message'), '<br>');

        $mform->addElement('static', 'todo', get_string('whattodo', 'local_message'));

        $choices = array();
        $choices['0'] = \core\output\notification::NOTIFY_WARNING;
        $choices['1'] = \core\output\notification::NOTIFY_SUCCESS;
        $choices['2'] = \core\output\notification::NOTIFY_ERROR;
        $choices['3'] = \core\output\notification::NOTIFY_INFO;
        $mform->addElement('select', 'messagetype', get_string('message_type', 'local_message'), $choices);
        $mform->setDefault('messagetype', '3');

        $mform->addElement('advcheckbox', 'deleteall', get_string('delete_all_selected', 'local_message'), get_string('yes'));

        $this->add_action_buttons();
    }
    function validation($data, $files) {
        return array();
    }
}
