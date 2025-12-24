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
 * Block carousel_educo - Main block class
 *
 * @package    block_carousel_educo
 * @copyright  2024 EduCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/theme/edash/inc/block_handler/get-content.php');

class block_carousel_educo extends block_base {

    /**
     * Initialize the block.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_carousel_educo');
    }

    /**
     * Set default configuration when block is first added.
     */
    public function specialization() {
        global $CFG;

        if (empty($this->config)) {
            $this->config = new stdClass();
            $this->config->itemsnumber = 1;
            $this->config->item_title1 = get_string('slide', 'block_carousel_educo') . ' 1';
            $this->config->item_text1 = '';
            $this->config->item_image1 = $CFG->wwwroot . '/theme/educo/pix/slide_country.jpg';
            $this->config->item_button1 = '';
            $this->config->item_link1 = '';
        }
    }

    /**
     * Generate and return the block content.
     *
     * @return stdClass The block content object.
     */
    public function get_content() {
        global $CFG, $OUTPUT;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text = '';

        // Get configuration data
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->itemsnumber = is_numeric($data->itemsnumber) ? (int)$data->itemsnumber : 1;
        } else {
            $data = new stdClass();
            $data->itemsnumber = 1;
        }

        // Ensure at least 1 slide
        if ($data->itemsnumber < 1) {
            $data->itemsnumber = 1;
        }

        // Get block context
        $context = context_block::instance($this->instance->id);

        // Generate unique ID for this carousel instance
        $carouselid = 'carousel_educo_' . $this->instance->id;

        // Start building HTML
        $html = '';
        $html .= '<div id="' . $carouselid . '" class="carousel slide carousel-educo" data-ride="carousel" data-interval="5000">';

        // Carousel indicators
        $html .= '<ol class="carousel-indicators">';
        for ($i = 0; $i < $data->itemsnumber; $i++) {
            $active = ($i === 0) ? ' class="active"' : '';
            $ariaCurrent = ($i === 0) ? ' aria-current="true"' : '';
            $html .= '<li data-target="#' . $carouselid . '" data-slide-to="' . $i . '"' . $active . $ariaCurrent;
            $html .= ' aria-label="' . s(get_string('slide', 'block_carousel_educo') . ' ' . ($i + 1)) . '"></li>';
        }
        $html .= '</ol>';

        // Carousel inner (slides)
        $html .= '<div class="carousel-inner">';

        for ($i = 1; $i <= $data->itemsnumber; $i++) {
            $activeClass = ($i === 1) ? ' active' : '';

            // Get image URL from file storage
            $imageurl = $this->get_image_url($context->id, $i);

            // If no image uploaded, check config or use default from theme
            if (empty($imageurl)) {
                if (!empty($data->{'item_image' . $i})) {
                    $imageurl = $data->{'item_image' . $i};
                } else {
                    $imageurl = $CFG->wwwroot . '/theme/educo/pix/slide_country.jpg';
                }
            }

            // Get slide content with proper sanitization
            $itemTitle = '';
            if (isset($data->{'item_title' . $i})) {
                $itemTitle = format_string($data->{'item_title' . $i});
            }

            $itemText = '';
            if (isset($data->{'item_text' . $i})) {
                $itemText = format_text($data->{'item_text' . $i}, FORMAT_PLAIN);
            }

            $buttonText = '';
            if (isset($data->{'item_button' . $i})) {
                $buttonText = format_string($data->{'item_button' . $i});
            }

            $buttonLink = '';
            if (isset($data->{'item_link' . $i})) {
                $buttonLink = $data->{'item_link' . $i};
                // Validate URL
                if (!empty($buttonLink) && !filter_var($buttonLink, FILTER_VALIDATE_URL)) {
                    // If not a valid URL, try to make it one
                    if (strpos($buttonLink, '/') === 0) {
                        $buttonLink = $CFG->wwwroot . $buttonLink;
                    } else {
                        $buttonLink = '';
                    }
                }
            }

            // Build slide HTML with original styles
            $html .= '<div class="carousel-item' . $activeClass . '">';
            $html .= '<img src="' . s($imageurl) . '" class="d-block w-100" style="height: 550px; object-fit: cover;" alt="' . s($itemTitle) . '">';

            // Caption overlay
            $html .= '<div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.4);">';

            if (!empty($itemTitle)) {
                $html .= '<h2 style="color: white;">' . $itemTitle . '</h2>';
            }

            if (!empty($itemText)) {
                $html .= '<p style="color: white; font-size: 20px;">' . $itemText . '</p>';
            }

            if (!empty($buttonText) && !empty($buttonLink)) {
                $html .= '<a href="' . s($buttonLink) . '" class="btn btn-primary" style="color: white;">' . $buttonText . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>'; // End carousel-item
        }

        $html .= '</div>'; // End carousel-inner

        // Navigation controls
        $html .= '<a class="carousel-control-prev" href="#' . $carouselid . '" role="button" data-slide="prev">';
        $html .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
        $html .= '<span class="sr-only">' . s(get_string('previous', 'block_carousel_educo')) . '</span>';
        $html .= '</a>';
        $html .= '<a class="carousel-control-next" href="#' . $carouselid . '" role="button" data-slide="next">';
        $html .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
        $html .= '<span class="sr-only">' . s(get_string('next', 'block_carousel_educo')) . '</span>';
        $html .= '</a>';

        $html .= '</div>'; // End carousel

        $this->content->text = $html;

        return $this->content;
    }

    /**
     * Get the URL for an image stored in the block's file area.
     *
     * @param int $contextid The context ID of the block.
     * @param int $itemid The item ID (slide number).
     * @return string The URL of the image, or empty string if not found.
     */
    private function get_image_url($contextid, $itemid) {
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $contextid,
            'block_carousel_educo',
            'slide_image',
            $itemid,
            'sortorder DESC, id ASC',
            false
        );

        foreach ($files as $file) {
            if ($file->is_valid_image()) {
                return moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false);
            }
        }

        return '';
    }

    /**
     * Allow multiple instances of this block.
     *
     * @return bool True to allow multiple instances.
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * This block does not have global configuration.
     *
     * @return bool False as there is no global config.
     */
    public function has_config() {
        return false;
    }

    /**
     * Define where this block can be used.
     *
     * @return array Array of page formats where block can be used.
     */
    public function applicable_formats() {
        return array(
            'all' => true,
            'my' => true,
            'admin' => false,
            'course-view' => true,
            'course' => true,
            'site-index' => true,
        );
    }

    /**
     * Allow block to have a title set by user.
     *
     * @return bool True to allow custom title.
     */
    public function instance_allow_config() {
        return true;
    }
}
