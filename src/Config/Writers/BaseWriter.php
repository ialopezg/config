<?php

namespace ialopezg\Libraries\Config\Writers;

use ialopezg\Libraries\ArrayUtils;
use ialopezg\Libraries\Config\Writers\WriterInterface;
use Traversable;

/**
 * Base file writer
 *
 * @package Libraries\Config
 */
abstract class BaseWriter implements WriterInterface {
    /**
     * @inheritDoc
     *
     * @throws \InvalidArgumentException if file name not specified
     * @throws \RuntimeException if error occurs while writing file process.
     * @throws \Exception if other error occurs
     */
    public function toFile($config, $filename, $lockExclusive = true) {
        if (empty($filename)) {
            throw new \InvalidArgumentException('No file name specified.');
        }

        $flags = 0;
        if ($lockExclusive) {
            $flags |= LOCK_EX;
        }

        set_error_handler(function ($error, $message) use ($filename) {
            throw new \RuntimeException(sprintf('Error writing to "%s": %s', $filename, $message), $error);
        }, E_WARNING);

        try {
            file_put_contents($filename, $this->toString($config), $flags);
        } catch (\Exception $e) {
            restore_error_handler();

            throw $e;
        }

        restore_error_handler();
    }

    /**
     * @inheritDoc
     */
    public function toString($config) {
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        } elseif (!is_array($config)) {
            throw new \InvalidArgumentException(__METHOD__ . ' expect an array or Traversable config');
        }

        return $this->processConfig($config);
    }

    /**
     * Process a config object into a readable format.
     *
     * @param array $config Config to process.
     *
     * @return string Configuration parsed.
     */
    abstract protected function processConfig(array $config);
}