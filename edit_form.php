<?php

class block_carousel_educo_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $CFG;

        $itemsnumber = 3;
        if(isset($this->block->config->itemsnumber)){
            $itemsnumber = $this->block->config->itemsnumber;
        }

        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        $itemsrange = range(1, 5);
        $mform->addElement('select', 'config_itemsnumber', get_string('config_itemsnumber', 'theme_edash'), $itemsrange);
        $mform->setDefault('config_itemsnumber', 3);

        for ($i = 1; $i <= 5; $i++) {
            $mform->addElement('header', 'config_edash_item' . $i, get_string('config_item', 'theme_edash') . " $i");

            $mform->addElement('text', 'config_item_title' . $i, get_string('config_title', 'theme_edash', $i));
            $mform->setDefault('config_item_title' . $i, "Slide $i Title");
            $mform->setType('config_item_title' . $i, PARAM_TEXT);

            $mform->addElement('textarea', 'config_item_text' . $i, get_string('config_text', 'theme_edash', $i));
            $mform->setDefault('config_item_text' . $i, "Slide $i Text");
            $mform->setType('config_item_text' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_item_image' . $i, 'Image URL');
            $mform->setDefault('config_item_image' . $i, "Image URL for Slide $i");
            $mform->setType('config_item_image' . $i, PARAM_TEXT);
        }
    }
}
?>
