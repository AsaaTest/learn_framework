<?php

namespace Learn\Session;

/**
 * The `PhpNativeSessionStorage` class implements the `SessionStorage` interface using PHP's native session handling functions.
 */
class PhpNativeSessionStorage implements SessionStorage
{
    /**
     * Start the session. This method should be called to initialize the session for storing data.
     *
     * @throws \RuntimeException If starting the session fails.
     */
    public function start()
    {
        if (!session_start()) {
            throw new \RuntimeException("Failed starting session");
        }
    }

    /**
     * Get the unique identifier for the session.
     *
     * @return string The session identifier.
     */
    public function id(): string
    {
        return session_id();
    }

    /**
     * Retrieve the value associated with a session key.
     *
     * @param string $key     The key for which to retrieve the value.
     * @param mixed  $default (optional) The default value to return if the key does not exist.
     *
     * @return mixed The value associated with the key, or the default value if the key is not found.
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a value for a session key. This method stores the key-value pair in the session.
     *
     * @param string $key   The key to set.
     * @param mixed  $value The value to associate with the key.
     */
    public function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Check if a specific key exists in the session.
     *
     * @param string $key The key to check.
     *
     * @return bool `true` if the key exists, `false` otherwise.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a specific key and its associated value from the session.
     *
     * @param string $key The key to remove.
     */
    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the session, clearing all stored data and ending the session.
     */
    public function destroy()
    {
        session_destroy();
    }
}
