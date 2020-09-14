<?php

namespace ialopezg\Libraries\Config;

/**
 * Base Config
 *
 * @package ialopezg\Libraries\Config
 */
abstract class BaseConfig implements \ArrayAccess, ConfigInterface, \Iterator {
    /** @var array Stores the configuration data. */
    protected $data = null;
    /** @var array Caches the configuration data. */
    protected $cache = [];

    /**
     * Sets default options, if any.
     *
     * @param array $params Data to be passed to this instance, if any.
     */
    public function __construct(array $params) {
        $this->data = array_merge($this->getDefaults(), $params);
    }

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null) {
        if ($this->has($key)) {
            return $this->cache[$key];
        }

        return $default;
    }

    /**
     * Returns an array of default options and values.
     *
     * @return array An array of default options and values.
     */
    protected function getDefaults() {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function has($key) {
        if (isset($this->cache[$key])) {
            return true;
        }

        $segments = explode('.', $key);
        $root = $this->data;

        // Nested lookup.
        foreach ($segments as $segment) {
            if (array_key_exists($segment, $root)) {
                $root = $root[$segment];

                continue;
            } else {
                return false;
            }
        }

        $this->cache[$key] = $root;

        return true;
    }

    /**
     * Merge config from another instance.
     *
     * @param ConfigInterface $config Configuration to merged.
     *
     * @return $this
     */
    public function merge(ConfigInterface $config) {
        $this->data = array_replace_recursive($this->data, $config->all());

        return $this;
    }

    /**
     * Remove a value by given key name.
     *
     * @param string $key Key name to remove.
     */
    public function remove($key) {
        $this->offsetUnset($key);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value) {
        $segments = explode('.', $key);
        $root = &$this->data;
        $cacheKey = '';

        // Look for the key, creating nested keys if needed
        while ($part = array_shift($segments)) {
            if ($cacheKey != '') {
                $cacheKey .= '.';
            }
            $cacheKey .= $part;
            if (!isset($root[$part]) && count($segments)) {
                $root[$part] = [];
            }
            $root = &$root[$part];

            //Unset all old nested cache
            if (isset($this->cache[$cacheKey])) {
                unset($this->cache[$cacheKey]);
            }

            //Unset all old nested cache in case of array
            if (count($segments) === 0) {
                foreach ($this->cache as $cache_key => $cache_value) {
                    if (substr($cache_key, 0, strlen($cacheKey)) === $cacheKey) {
                        unset($this->cache[$cache_key]);
                    }
                }
            }
        }

        // Assign value at target node
        $this->cache[$key] = $root = $value;
    }

    /**
     * Checks if a key exists.
     *
     * @param mixed $offset A key to check for.
     *
     * @return bool <code>true</code> on success or false on failure. The return value will be casted to boolean if
     * non-boolean was returned.
     */
    public function offsetExists($offset) {
        return $this->has($offset);
    }

    /**
     * Gets a value using the offset as a key.
     *
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset) {
        return $this->get($offset);
    }

    /**
     * Sets a value using the offset as a key.
     *
     * @param mixed $offset The key to assign the value to.
     * @param mixed $value The value to set.
     */
    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    /**
     * Deletes a key and its value.
     *
     * @param mixed $offset The key to unset.
     */
    public function offsetUnset($offset) {
        $this->set($offset, null);
    }

    /**
     * Returns the current data array element.
     *
     * @return mixed|bool|null The element referenced by the data array's internal cursor.
     * If the array is empty or there is no element at the cursor, the function returns false.
     * If the array is undefined, the function returns null.
     */
    public function current() {
        return is_array($this->data) ? current($this->data) : null;
    }

    /**
     * Returns the key from data array index referenced by its internal cursor.
     *
     * @return mixed|bool|null The index referenced by the data array's internal cursor.
     * If the array is empty or undefined or there is no element at the cursor, the function returns null.
     */
    public function key() {
        return is_array($this->data) ? key($this->data) : null;
    }

    /**
     * Moves the data array's internal cursor forward one element.
     *
     * @return mixed|bool|null The element referenced by the data array's internal cursor after the move is
     * completed. If there are no more elements in the array after the move, the function returns false. If
     * the data array is undefined, the function returns null.
     */
    public function next() {
        return is_array($this->data) ? next($this->data) : null;
    }

    /**
     * Moves the data array's internal cursor to the first element.
     *
     * @return bool|mixed|null The element referenced by the data array's internal cursor after the move is
     * completed. If the data array is empty, the function returns false. If the data array is undefined,
     * the function returns null.
     */
    public function rewind() {
        return is_array($this->data) ? reset($this->data) : null;
    }

    /**
     * Tests whether the current index is valid in the iterator.
     *
     * @return bool <code>true</code> if the current index is valid; <code>false</code> otherwise.
     */
    public function valid() {
        return is_array($this->data) ? key($this->data) !== null : false;
    }
}
