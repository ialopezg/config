<?php


namespace ialopezg\Libraries\Config\Readers;

/**
 * Configuration File Reader Interface.
 *
 * @package ialopezg\Libraries\Config
 */
interface ReaderInterface {
    /**
     * Returns an array of allowed file extensions for this parser.
     *
     * @return array An array containing allowed file extensions for this parser.
     */
    public static function getSupportedExtensions();

    /**
     * Parses a configuration from string `$config` and gets its contents as an array.
     *
     * @param string $config Value to be parsed.
     *
     * @return array An array containing configuration values requested.
     */
    public function parseString($config);

    /**
     * Read configuration from file `$filename` and gets its contents as an array.
     *
     * @param string $filename file name to read.
     *
     * @return array an array containing configuration values.
     */
    public function readFile($filename);
}
