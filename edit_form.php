<?php
// This file is part of Moodle - http://moodle.org/
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
 * Block carousel_educo - Edit form
 *
 * @package    block_carousel_educo
 * @copyright  2024 EduCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Form for editing carousel_educo block instances.
 */
class block_carousel_educo_edit_form extends block_edit_form {

    /** @var int Maximum number of slides allowed */
    const MAX_SLIDES = 5;

    /** @var string File area for slide images */
    const FILE_AREA = 'slide_image';

    /**
     * Define the form fields specific to this block.
     *
     * @param MoodleQuickForm $mform The form being built.
     */
    protected function specific_definition($mform) {
        global $CFG;

        // Determine current number of slides
        $itemsnumber = 1;
        if (isset($this->block->config->itemsnumber) && is_numeric($this->block->config->itemsnumber)) {
            $itemsnumber = (int)$this->block->config->itemsnumber;
            if ($itemsnumber < 1) {
                $itemsnumber = 1;
            }
            if ($itemsnumber > self::MAX_SLIDES) {
                $itemsnumber = self::MAX_SLIDES;
            }
        }

        // Block settings header
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block_carousel_educo'));

        // Number of slides dropdown - values are 1-5, keys are also 1-5
        $slidesoptions = array();
        for ($i = 1; $i <= self::MAX_SLIDES; $i++) {
            $slidesoptions[$i] = $i;
        }
        $mform->addElement('select', 'config_itemsnumber', get_string('config_items', 'block_carousel_educo'), $slidesoptions);
        $mform->setDefault('config_itemsnumber', 1);
        $mform->setType('config_itemsnumber', PARAM_INT);

        // File manager options for images
        $fileoptions = array(
            'subdirs' => 0,
            'maxbytes' => $CFG->maxbytes,
            'areamaxbytes' => 10485760, // 10MB
            'maxfiles' => 1,
            'accepted_types' => array('web_image')
        );

        // Create form fields for each slide (always show fields for current configured number)
        // We show all 5 to allow adding more slides
        for ($i = 1; $i <= self::MAX_SLIDES; $i++) {
            // Slide header
            $mform->addElement('header', 'config_slide_header' . $i, get_string('config_item', 'block_carousel_educo', $i));

            // All slides expanded; hideIf controls visibility dynamically
            $mform->setExpanded('config_slide_header' . $i, true);

            // Title field
            $mform->addElement('text', 'config_item_title' . $i, get_string('config_title', 'block_carousel_educo', $i));
            $mform->setDefault('config_item_title' . $i, '');
            $mform->setType('config_item_title' . $i, PARAM_TEXT);

            // Text/description field
            $mform->addElement('textarea', 'config_item_text' . $i, get_string('config_text', 'block_carousel_educo', $i),
                array('rows' => 4, 'cols' => 50));
            $mform->setDefault('config_item_text' . $i, '');
            $mform->setType('config_item_text' . $i, PARAM_TEXT);

            // Image file manager
            $mform->addElement('filemanager', 'config_item_image' . $i, get_string('config_image', 'block_carousel_educo', $i),
                null, $fileoptions);

            // Button text
            $mform->addElement('text', 'config_item_button' . $i, get_string('config_button', 'block_carousel_educo', $i));
            $mform->setDefault('config_item_button' . $i, '');
            $mform->setType('config_item_button' . $i, PARAM_TEXT);

            // Button link URL
            $mform->addElement('text', 'config_item_link' . $i, get_string('config_link', 'block_carousel_educo', $i),
                array('size' => 50));
            $mform->setDefault('config_item_link' . $i, '');
            $mform->setType('config_item_link' . $i, PARAM_URL);

            // Dynamic visibility: hide slide sections beyond the selected number.
            // hideIf with 'eq' acts as OR when called multiple times on the same element.
            if ($i > 1) {
                $slideelements = array(
                    'config_slide_header' . $i,
                    'config_item_title' . $i,
                    'config_item_text' . $i,
                    'config_item_image' . $i,
                    'config_item_button' . $i,
                    'config_item_link' . $i,
                );
                for ($j = 1; $j < $i; $j++) {
                    foreach ($slideelements as $element) {
                        $mform->hideIf($element, 'config_itemsnumber', 'eq', (string)$j);
                    }
                }
            }
        }
    }

    /**
     * Prepare data for the form.
     * This loads existing images into the file manager.
     *
     * @param stdClass $defaults Default values for the form.
     */
    public function set_data($defaults) {
        if (!empty($this->block->instance->id)) {
            $context = context_block::instance($this->block->instance->id);

            // Prepare file areas for all possible slides
            for ($i = 1; $i <= self::MAX_SLIDES; $i++) {
                $draftitemid = file_get_submitted_draft_itemid('config_item_image' . $i);

                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    'block_carousel_educo',
                    self::FILE_AREA,
                    $i,
                    array('subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => array('web_image'))
                );

                $defaults->{'config_item_image' . $i} = $draftitemid;
            }
        }

        parent::set_data($defaults);
    }

    /**
     * Get data from the form.
     * This saves uploaded images to the file area.
     *
     * @return stdClass|null The form data, or null if cancelled.
     */
    public function get_data() {
        $data = parent::get_data();

        if ($data && !empty($this->block->instance->id)) {
            $context = context_block::instance($this->block->instance->id);

            // Ensure itemsnumber is valid
            $itemsnumber = isset($data->config_itemsnumber) ? (int)$data->config_itemsnumber : 1;
            if ($itemsnumber < 1) {
                $itemsnumber = 1;
            }
            if ($itemsnumber > self::MAX_SLIDES) {
                $itemsnumber = self::MAX_SLIDES;
            }
            $data->config_itemsnumber = $itemsnumber;

            // Save images for all slides that have data
            for ($i = 1; $i <= self::MAX_SLIDES; $i++) {
                $fieldname = 'config_item_image' . $i;
                if (isset($data->$fieldname)) {
                    file_save_draft_area_files(
                        $data->$fieldname,
                        $context->id,
                        'block_carousel_educo',
                        self::FILE_AREA,
                        $i,
                        array('subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => array('web_image'))
                    );
                }
            }
        }

        return $data;
    }
}
