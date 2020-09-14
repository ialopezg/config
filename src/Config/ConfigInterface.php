<?php

namespace ialopezg\Libraries\Config;

/**
 * Config Interface
 *
 * @package ialopezg\Libraries\Config
 */
interface ConfigInterface {
    /**
     * Get all the configuration items.
     *
     * @return array An array containing all configuration items.
     */
    public function all();

    /**
     * Gets a configuration setting using a simple or nested key.
     * Nested keys are similar to JSON paths that use the dot
     * dot notation.
     *
     * @param string $key Requested key.
     * @param mixed|null $default Default value.
     *
     * @return mixed Return the value of requested key or default value if not found.
     */
    public function get($key, $default = null);

    /**
     * Checks if configuration value exist, using either simple or nested keys.
     *
     * @param string $key Requested key.
     * @return bool <code>true</code> if key exist, otherwise <code>false</code>.
     */
    public function has($key);

    /**
     * Sets configuration values, using either simple or nested keys.
     *
     * @param string $key Key name to set.
     * @param mixed $value Value to set.
     *
     * @return void
     */
    public function set($key, $value);
}
