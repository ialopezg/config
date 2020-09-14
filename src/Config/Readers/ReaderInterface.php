<?php


namespace ialopezg\Libraries\Config\Readers;


interface ReaderInterface {
    /**
     * Returns an array of allowed file extensions for this parser.
     *
     * @return array An array containing allowed file extensions for this parser.
     */
    public static function getSupportedExtensions();
}