<?php


namespace ialopezg\Libraries\Config\Readers;

class PHPReader implements ReaderInterface {
    /**
     * @inheritDoc
     */
    public static function getSupportedExtensions() {
        return ['php'];
    }
}