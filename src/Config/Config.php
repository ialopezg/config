<?php

namespace ialopezg\Libraries\Config;

use ialopezg\Libraries\Config\Readers\ReaderInterface;
use ialopezg\Libraries\Exceptions\FileNotFoundException;
use ialopezg\Libraries\Exceptions\UnsupportedFormatException;

class Config extends BaseConfig {
    /** @var array Supported readers.  */
    protected $supportedReaders = ['ialopezg\Libraries\Config\Readers\PHPReader'];
    /** @var array Supported writers. */
    protected $supportedWriters = ['ialopezg\Libraries\Config\Writers\PHPWriter'];

    /**
     * Loads a Config instance.
     *
     * @param array|string $params Filenames or string with configuration.
     * @param ReaderInterface $reader Configuration parser.
     *
     * @throws FileNotFoundException if file could not located.
     * @throws UnsupportedFormatException if file format is not supported.
     */
    public function __construct($params, $reader = null) {
        if (is_file($params)) {
            $this->loadFromFile($params, $reader);
        } elseif (is_string($params)) {
            $this->loadFromString($params, $reader);
        }

        parent::__construct($this->data);
    }

    /**
     * Gets a configuration file reader for given format.
     *
     * @param string $format configuration file format.
     *
     * @return ReaderInterface a configuration file reader instance.
     * @throws UnsupportedFormatException if `$format` specified is not supported.
     */
    protected function getReader($format) {
        foreach ($this->supportedReaders as $reader) {
            if (in_array($format, $reader::getSupportedExtensions())) {
                return new $reader();
            }
        }

        throw new UnsupportedFormatException("The configuration file format '{$format}' is not supported.");
    }

    /**
     * Loads a Config class instance.
     *
     * @param array|string $params Filenames or string with configuration.
     * @param ReaderInterface $reader Configuration parser.
     *
     * @return Config
     * @throws FileNotFoundException if filename could not locate.
     * @throws UnsupportedFormatException if file format is not supported.
     */
    public static function load($params, $reader = null) {
        return new static($params, $reader);
    }

    /**
     * Loads configuration from file. If not specified try to load default config file.
     *
     * @param string $filename file to load.
     * @param ReaderInterface $reader configuration file reader.
     *
     * @throws FileNotFoundException if filename cannot be located.
     * @throws UnsupportedFormatException if file format is not supported.
     */
    public function loadFromFile($filename = '', $reader = null) {
        if (!file_exists($filename)) {
            throw new FileNotFoundException($filename);
        }
        $extension = pathinfo($filename)['extension'];
        $key = substr($filename, 0, -(strlen($extension) + 1));

        if (is_null($reader)) {
            $reader = $this->getReader($extension);
        }

        if (is_null($this->data)) {
            $this->data = [];
        }
        $this->data = array_replace_recursive($this->data, $reader->readFile($filename));
    }

    /**
     * Loads configuration from string.
     *
     * @param string $config configuration string to parse.
     * @param ReaderInterface $parser configuration parser.
     */
    public function loadFromString($config, $parser = null) {
        if (is_null($this->data)) {
            $this->data = [];
        }
        $this->data = array_replace_recursive($this->data, $parser->parseString($config));
    }

    public function toFile($filename, $writer = null) {

    }
}
