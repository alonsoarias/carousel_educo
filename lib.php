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
 * Block carousel_educo - Library functions
 *
 * @package    block_carousel_educo
 * @copyright  2024 EduCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Serves files for the carousel_educo block.
 *
 * @param stdClass $course The course object.
 * @param stdClass $cm The course module object (not used for blocks).
 * @param context $context The context of the block.
 * @param string $filearea The file area within the block.
 * @param array $args Additional arguments for file serving.
 * @param bool $forcedownload Whether to force the download of the file.
 * @param array $options Additional options affecting the file serving.
 * @return bool False if file not found or access not allowed, otherwise sends the file.
 */
function block_carousel_educo_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $CFG;

    // Ensure the context level is correct for a block.
    if ($context->contextlevel != CONTEXT_BLOCK) {
        return false;
    }

    // Only allow access to the slide_image file area.
    if ($filearea !== 'slide_image') {
        return false;
    }

    // Require user to be logged in.
    require_login();

    // Extract itemid (slide number) from the arguments.
    $itemid = array_shift($args);
    if ($itemid === null) {
        return false;
    }

    // Extract filename from the arguments.
    $filename = array_pop($args);
    if (empty($filename)) {
        return false;
    }

    // Build the filepath from any remaining arguments.
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    // Get the file storage instance.
    $fs = get_file_storage();

    // Retrieve the file from storage.
    $file = $fs->get_file($context->id, 'block_carousel_educo', $filearea, $itemid, $filepath, $filename);

    // Check if the file exists and is not a directory.
    if (!$file || $file->is_directory()) {
        return false;
    }

    // Set caching options for better performance.
    $options['cacheability'] = 'public';
    $options['immutable'] = false;

    // Send the file to the browser.
    send_stored_file($file, null, 0, $forcedownload, $options);
}

/**
 * Get the file areas used by this block.
 *
 * @return array Array of file area names.
 */
function block_carousel_educo_get_file_areas() {
    return array('slide_image');
}
