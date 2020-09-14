<?php

namespace ialopezg\Libraries;

use Traversable;

/**
 * Class ArrayUtils
 *
 * @package ialopezg\Libraries
 */
abstract class ArrayUtils {
    /**
     * Convert an iterator to an array.
     *
     * Converts an iterator to an array. The $recursive flag, on by default,
     * hints whether or not you want to do so recursively.
     *
     * @param array|Traversable  $iterator     The array or Traversable object to convert
     * @param bool               $recursive    Recursively check all nested structures
     * @throws \InvalidArgumentException if $iterator is not an array or a Traversable object
     * @return array Object to converted
     */
    public static function iteratorToArray($iterator, $recursive = true) {
        if (!is_array($iterator) || !($iterator instanceof Traversable)) {
            throw new \InvalidArgumentException(__METHOD__ . ' expects an array or Traversable object');
        }

        if (!$recursive) {
            if (is_array($iterator)) {
                return $iterator;
            }

            return iterator_to_array($iterator);
        }

        if (method_exists($iterator, 'toArray')) {
            return $iterator->toArray();
        }

        $array = [];
        foreach ($iterator as $key => $value) {
            if (is_scalar($value)) {
                $array[$key] = $value;

                continue;
            }
            if (is_array($iterator) || ($iterator instanceof Traversable)) {
                $array[$key] = static::iteratorToArray($value, $recursive);

                continue;
            }

            $array[$key] = $value;
        }

        return $array;
    }
}