<?php

namespace ialopezg\Libraries\Config\Writers;

/**
 * PHP file writer.
 *
 * @package ialopezg\Libraries\Config
 */
class PHPWriter extends BaseWriter {
    const INDENT_STRING = '    ';

    /** @var bool Whether if use bracket syntax. */
    protected $useBracketSyntax = true;

    /** @var bool  Whether if use ClassName as scalars. */
    protected $useClassNameScalars = false;

    /**
     * Check whether a string represents a resolvable FQCN.
     *
     * @param string $string Value to check.
     *
     * @return bool <code>true</code> if string is a resolvable FQCN, <code>false</code> otherwise.
     */
    protected function checkStringIsFNQ($string) {
        if (!preg_match('/^(?:\x5c[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/', $string)) {
            return false;
        }

        return class_exists($string) || interface_exists($string) || trait_exists($string);
    }

    /**
     * Attempts to convert a FQN string to class name scalar.
     *
     * @param string $string String to check.
     *
     * @return false|string Returns <code>false</code> if string is not a valid FQN or can not be resolved to
     * an existing name.
     */
    protected function fqnStringToClassNameScalar($string) {
        if (strlen($string) < 1) {
            return false;
        }

        if ($string[0] !== '\\') {
            $string = '\\' . $string;
        }

        if ($this->checkStringIsFNQ($string)) {
            return "{$string}::class";
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public static function getSupportedExtensions() {
        return ['php'];
    }

    /**
     * @inheritDoc
     */
    protected function processConfig(array $config) {
        $syntax = [
            'open' => $this->useBracketSyntax ? '[' : 'array(',
            'close' => $this->useBracketSyntax ? ']' : ')'
        ];

        return "<?php\n" .
            "return {$syntax['open']}\n" . $this->processIndented($config, $syntax) . "{$syntax['close']};\n";
    }

    protected function processIndented(array $config, array $syntax, &$indentLevel = 1) {
        $result = '';
        foreach ($config as $key => $value) {
            $result .= str_repeat(self::INDENT_STRING, $indentLevel);
            $result .= is_int($key) ? $key : $this->processKey($key) . ' => ';
            // Check nested values
            if (is_array($value)) {
                if ($value === []) {
                    $result .= "{$syntax['open']}{$syntax['close']},\n";
                } else {
                    $indentLevel++;
                    $result .= "{$syntax['open']}\n" .
                        $this->processIndented($value, $syntax, $indentLevel) .
                        str_repeat(self::INDENT_STRING, --$indentLevel) . "{$syntax['close']},\n";
                }
            } elseif (is_object($value)) {
                $result .= var_export($value, true) . ",\n";
            } elseif (is_string($value)) {
                $result .= $this->processValue($value) . ",\n";
            } elseif (is_bool($value)) {
                $result .= ($value ? 'true' : 'false') . ",\n";
            } elseif (is_null($value)) {
                $result .= "null,\n";
            } else {
                $result .= "{$value},\n";
            }
        }

        return $result;
    }

    /**
     * Process a string configuration key.
     *
     * @param string $key String to process.
     *
     * @return bool|string Key processed, <code>false</code> otherwise.
     */
    protected function processKey($key) {
        if ($this->useClassNameScalars && false !== ($fnKey = $this->fqnStringToClassNameScalar($key))) {
            return $fnKey;
        }

        return "'" . addslashes($key) . "'";
    }

    /**
     * Process a string configuration value.
     *
     * @param string $value Value to process.
     * @return false|string Value processed, <code>false</code> otherwise.
     */
    protected function processValue($value) {
        if ($this->useClassNameScalars && false !== ($fqnValue = $this->fqnStringToClassNameScalar($value))) {
            return $fqnValue;
        }

        return var_export($value, true);
    }

    /**
     * Sets whether or not to use the PHP 5.4+ "[]" array syntax.
     *
     * @param bool $value Value to set.
     *
     * @return self
     */
    public function setUseBracketSyntax($value) {
        $this->useBracketSyntax = $value;

        return $this;
    }

    /**
     * Sets whether or not to render resolvable FQN strings as scalars, using PHP 5.5+ class-keyword.
     *
     * @param bool $value Value to set.
     *
     * @return self
     */
    public function setUseClassNameScalars($value) {
        $this->useClassNameScalars = $value;

        return $this;
    }
}