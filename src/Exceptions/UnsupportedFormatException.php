<?php

namespace ialopezg\Libraries\Exceptions;

/**
 * Occurs when format file specified is not supported.
 *
 * @package ialopezg\Libraries\Exceptions
 */
class UnsupportedFormatException extends \ErrorException {
    /**
     * Construct the exception.
     *
     * @param string $format Format not supported.
     */
    public function __construct($format) {
        parent::__construct("The configuration file format '{$format}' not supported.", 0, 1, __FILE__, __LINE__, null);
    }
}