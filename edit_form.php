<?php

class block_carousel_educo_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $CFG;

        $itemsnumber = 1;
        if (isset($this->block->config->itemsnumber)) {
            $itemsnumber = $this->block->config->itemsnumber;
        }

        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block_carousel_educo'));

        $itemsrange = range(1, 5);
        $mform->addElement('select', 'config_itemsnumber', get_string('config_itemsnumber', 'block_carousel_educo'), $itemsrange);
        $mform->setDefault('config_itemsnumber', 1);

        for ($i = 1; $i <= 5; $i++) {
            $mform->addElement('header', 'config_edash_item' . $i, get_string('config_item', 'block_carousel_educo') . " $i");

            $mform->addElement('text', 'config_item_title' . $i, get_string('config_title', 'block_carousel_educo', $i));
            $mform->setDefault('config_item_title' . $i, "Curso $i Título");
            $mform->setType('config_item_title' . $i, PARAM_TEXT);

            $mform->addElement('textarea', 'config_item_text' . $i, get_string('config_text', 'block_carousel_educo', $i));
            $mform->setDefault('config_item_text' . $i, "Texto de la Diapositiva $i");
            $mform->setType('config_item_text' . $i, PARAM_TEXT);

            $mform->addElement('filemanager', 'config_item_image' . $i, get_string('config_image', 'block_carousel_educo', $i), null, array('accepted_types' => array('image')));

            $mform->addElement('text', 'config_item_button' . $i, get_string('config_button', 'block_carousel_educo', $i));
            $mform->setDefault('config_item_button' . $i, "");
            $mform->setType('config_item_button' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_item_link' . $i, get_string('config_link', 'block_carousel_educo', $i));
            $mform->setDefault('config_item_link' . $i, "");
            $mform->setType('config_item_link' . $i, PARAM_URL);
        }
    }

    public function set_data($defaults) {
        if (isset($this->block->config)) {
            $contextid = $this->block->context->id;
            for ($i = 1; $i <= 5; $i++) {
                $draftitemid = file_get_submitted_draft_itemid('config_item_image' . $i);
                file_prepare_draft_area($draftitemid, $contextid, 'block_carousel_educo', 'content', $i, array('subdirs' => false));
                $defaults->{'config_item_image' . $i} = $draftitemid;
            }
        }
        parent::set_data($defaults);
    }

    public function get_data() {
        $data = parent::get_data();
        if ($data !== null) {
            $contextid = $this->block->context->id;
            for ($i = 1; $i <= 5; $i++) {
                file_save_draft_area_files($data->{'config_item_image' . $i}, $contextid, 'block_carousel_educo', 'content', $i, array('subdirs' => false));
            }
        }
        return $data;
    }
}
?>
