<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Serves files for the carousel_educo block.
 *
 * @param stdClass $course The course object
 * @param stdClass $cm The course module object
 * @param context $context The context of the block
 * @param string $filearea The file area within the block
 * @param array $args Additional arguments for file serving
 * @param bool $forcedownload Whether to force the download of the file
 * @param array $options Additional options affecting the file serving
 * @return bool False if file not found or access not allowed
 */
function block_carousel_educo_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $DB, $CFG;

    // Require login to access files
    require_login();

    // Ensure the context level is correct
    if ($context->contextlevel != CONTEXT_BLOCK) {
        return false;
    }

    // Check the file area
    if ($filearea !== 'content') {
        return false;
    }

    // Extract itemid and filename from arguments
    $itemid = array_shift($args); // The itemid identifies the specific file
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    // Get the file storage object
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'block_carousel_educo', $filearea, $itemid, $filepath, $filename);

    // Check if the file exists and is not a directory
    if (!$file || $file->is_directory()) {
        return false;
    }

    // Send the file
    send_stored_file($file, 0, 0, true, $options);
}
