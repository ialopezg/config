<?php

namespace ialopezg\Libraries\Config\Writers;

/**
 * Write file interface.
 *
 * @package ialopezg\Libraries\Config\Writers
 */
interface WriterInterface {
    /**
     * Returns an array of allowed file extensions for this writer.
     *
     * @return array An array of allowed file extensions for this writer.
     */
    public static function getSupportedExtensions();

    /**
     * Write a config object to a file.
     *
     * @param array $config Configuration to write.
     * @param string $filename File where config will be written.
     * @param bool $lock Whether if lock file while writing.
     * @return mixed
     */
    public function toFile($config, $filename, $lock = true);

    /**
     * Write a config object to a string.
     *
     * @param mixed $config Object to parse.
     *
     * @return mixed Object parsed as string.
     */
    public function toString($config);
}