<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * MIME detection code.
 *
 * @version $Id$
 * @package phpMyAdmin
 * @todo Maybe we could try to use fileinfo module if loaded
 */

/**
 * Tries to detect MIME type of content.
 */
function PMA_detectMIME(&$test) {
    $len = strlen($test);
    if ($len >= 2 && $test[0] == chr(0xff) && $test[1] == chr(0xd8)) {
        return 'image/jpeg';
    }
    if ($len >= 3 && substr($test, 0, 3) == 'GIF') {
        return 'image/gif';
    }
    if ($len >= 4 && substr($test, 0, 4) == "\x89PNG") {
        return 'image/png';
    }
    return 'application/octet-stream';
}
?>
