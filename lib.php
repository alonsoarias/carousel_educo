<?php
function block_educo_categories_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_BLOCK && $filearea === 'slideimage') {
        $itemid = array_shift($args); // Obtener el itemid
        $relativepath = implode('/', $args);
        $fullpath = "/$context->id/block_educo_categories/$filearea/$itemid/$relativepath";

        $fs = get_file_storage();
        $file = $fs->get_file_by_hash(sha1($fullpath));
        if (!$file || $file->is_directory()) {
            send_file_not_found();
        }
        send_stored_file($file, 0, 0, $forcedownload, $options);
    }
    return false;
}
