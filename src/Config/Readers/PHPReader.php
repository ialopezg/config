<?php

namespace ialopezg\Libraries\Config\Readers;

use ialopezg\Libraries\Exceptions\ParserException;
use ialopezg\Libraries\Exceptions\UnsupportedFormatException;

/**
 * PHP Configuration File Reader
 *
 * @package ialopezg\Libraries\Config\Readers
 */
class PHPReader implements ReaderInterface {
    /**
     * @inheritDoc
     */
    public static function getSupportedExtensions() {
        return ['php'];
    }

    /**
     * Runs PHP string in isolated method
     *
     * @param  string $string
     *
     * @return array PHP code string if not error.
     */
    protected function isolate($string) {
        return eval($string);
    }

    /**
     * Completes parsing of PHP data.
     *
     * @param array|callable $data data to parse.
     *
     * @throws UnsupportedFormatException if data contains invalid format.
     * @return array data parsed.
     */
    public function parse($data = null) {
        if (is_callable($data)) {
            $data = call_user_func($data);
        }

        if (!is_array($data)) {
            throw new UnsupportedFormatException('PHP data does not return an array.');
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     *
     * @return array data parsed.
     * @throws ParserException|UnsupportedFormatException if the configuration does contains an
     * invalid format or not supported.
     */
    public function parseString($config) {
        $config = trim($config);
        if (substr($config, 0, 2) === '<?') {
            $config = '?>' . $config;
        }

        try {
            $data = $this->isolate($config);
        } catch (\Exception $e) {
            throw new ParserException([
                'message'   => 'PHP string threw an exception',
                'exception' => $e
            ]);
        }

        // Complete parsing
        return (array)$this->parse($data);
    }

    /**
     * {@inheritDoc}
     *
     * @return array an array containing configuration values.
     * @throws ParserException|UnsupportedFormatException if the configuration does contains an
     * invalid format or not supported.
     */
    public function readFile($filename) {
        try {
            $data = include $filename;
        } catch (\Exception $e) {
            throw new ParserException([
                'message' => 'PHP file threw an exception',
                'exception' => $e
            ]);
        }

        // Complete parsing
        return (array)$this->parse($data);
    }
}
