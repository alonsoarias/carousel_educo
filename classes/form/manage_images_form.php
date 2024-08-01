<?php

require_once($CFG->libdir . '/formslib.php');

class manage_images_form extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form;

        // Title field
        $mform->addElement('text', 'title', get_string('title', 'block_carousel_educo'));
        $mform->setType('title', PARAM_NOTAGS);

        // Description field
        $mform->addElement('textarea', 'description', get_string('description', 'block_carousel_educo'));
        $mform->setType('description', PARAM_NOTAGS);

        // Filepicker for image upload
        $mform->addElement('filepicker', 'image', get_string('image', 'block_carousel_educo'), null, array('maxbytes' => 2097152, 'accepted_types' => array('image')));

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        $errors = array();
        if (empty($data['title'])) {
            $errors['title'] = get_string('required');
        }
        return $errors;
    }
}
?>
