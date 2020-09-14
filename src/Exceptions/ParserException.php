<?php

namespace ialopezg\Libraries\Exceptions;

/**
 * ParserException occurs when something went wrong while parsing a configuration file.
 */
class ParserException extends \ErrorException {
    /**
     * ParserException constructor.
     *
     * @param array $error Error data.
     */
    public function __construct(array $error) {
        $message = $error['message'] ?: 'There was an error parsing the file';
        $code = isset($error['code']) ? $error['code'] : 0;
        $severity = isset($error['severity']) ? $error['severity'] : 1;
        $file = isset($error['file']) ? $error['file'] : __FILE__;
        $line = isset($error['line']) ? $error['line'] : __LINE__;
        $exception = isset($error['exception']) ? $error['exception'] : null;

        parent::__construct($message, $code, $severity, $file, $line, $exception);
    }
}