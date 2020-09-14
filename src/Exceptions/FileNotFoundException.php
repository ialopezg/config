<?php

namespace ialopezg\Libraries\Exceptions;

/**
 * Occurs when a file could not located.
 *
 * @package ialopezg\Libraries\Exceptions
 */
class FileNotFoundException extends \ErrorException {
    /**
     * Construct the exception.
     *
     * @param string $filename Exception filename.
     */
    public function __construct($filename) {
        parent::__construct("File '{$filename}' does not exists.", 0, 1, __FILE__, __LINE__, null);
    }
}