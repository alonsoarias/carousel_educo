<?php

function block_carousel_educo_pluginfile($course, $birecordorcm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel != CONTEXT_BLOCK) {
        send_file_not_found();
    }

    if ($filearea !== 'content') {
        send_file_not_found();
    }

    $fs = get_file_storage();

    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    if (!$file = $fs->get_file($context->id, 'block_carousel_educo', $filearea, $itemid, $filepath, $filename) or $file->is_directory()) {
        send_file_not_found();
    }

    send_stored_file($file, 60 * 60, 0, $forcedownload, $options);
}
?>
