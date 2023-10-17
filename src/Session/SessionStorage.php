<?php

namespace Learn\Session;

/**
 * The `SessionStorage` interface represents a contract for interacting with session storage in PHP.
 */
interface SessionStorage
{
    /**
     * Start the session. This method should be called to initialize the session for storing data.
     */
    public function start();

    /**
     * Get the unique identifier for the session.
     *
     * @return string The session identifier.
     */
    public function id(): string;

    /**
     * Retrieve the value associated with a session key.
     *
     * @param string $key     The key for which to retrieve the value.
     * @param mixed  $default (optional) The default value to return if the key does not exist.
     *
     * @return mixed The value associated with the key, or the default value if the key is not found.
     */
    public function get(string $key, $default = null);

    /**
     * Set a value for a session key. This method stores the key-value pair in the session.
     *
     * @param string $key   The key to set.
     * @param mixed  $value The value to associate with the key.
     */
    public function set(string $key, mixed $value);

    /**
     * Check if a specific key exists in the session.
     *
     * @param string $key The key to check.
     *
     * @return bool `true` if the key exists, `false` otherwise.
     */
    public function has(string $key): bool;

    /**
     * Remove a specific key and its associated value from the session.
     *
     * @param string $key The key to remove.
     */
    public function remove(string $key);

    /**
     * Destroy the session, clearing all stored data and ending the session.
     */
    public function destroy();
}
