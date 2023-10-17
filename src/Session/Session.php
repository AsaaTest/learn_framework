<?php

namespace Learn\Session;

/**
 * The `Session` class provides an interface to manage session data using a specific `SessionStorage` implementation.
 */
class Session
{
    /**
     * The session storage implementation.
     *
     * @var SessionStorage
     */
    protected SessionStorage $storage;

    /**
     * Key for flas session
     */
    public const FLASH_KEY = '_flash';

    /**
     * Create a new instance of the `Session` class with the specified session storage.
     *
     * @param SessionStorage $storage The session storage implementation to use.
     */
    public function __construct(SessionStorage $storage)
    {
        $this->storage = $storage;
        $this->storage->start();

        // Check if the session storage has the FLASH_KEY.
        if (!$this->storage->has(self::FLASH_KEY)) {
            // If not, initialize it with empty 'old' and 'new' arrays for flash data.
            $this->storage->set(self::FLASH_KEY, ['old' => [], 'new' => []]);
        }
    }

    /**
     * The destructor method is called when the `Session` object is destroyed.
     * It's responsible for cleaning up old flash data, aging flash data, and saving changes to the session storage.
     */
    public function __destruct()
    {
        // Remove keys associated with old flash data.
        foreach ($this->storage->get(self::FLASH_KEY)['old'] as $key) {
            $this->storage->remove($key);
        }

        // Age the flash data by moving 'new' data to 'old' and clearing 'new'.
        $this->ageFlashData();

        // Save any changes made to the session storage.
        $this->storage->save();
    }

    /**
     * Age the flash data by moving 'new' data to 'old' and clearing 'new' for the next request.
     */
    public function ageFlashData()
    {
        $flash = $this->storage->get(self::FLASH_KEY);
        $flash['old'] = $flash['new'];
        $flash['new'] = [];
        $this->storage->set(self::FLASH_KEY, $flash);
    }

    /**
     * Flash a key-value pair to the session. Flashed data is available only for the next request.
     *
     * @param string $key   The key to flash.
     * @param mixed  $value The value to flash.
     */
    public function flash(string $key, mixed $value)
    {
        $this->storage->set($key, $value);
        $flash = $this->storage->get(self::FLASH_KEY);
        $flash['new'][] = $key;
        $this->storage->set(self::FLASH_KEY, $flash);
    }

    /**
     * Get the unique identifier for the session.
     *
     * @return string The session identifier.
     */
    public function id(): string
    {
        return $this->storage->id();
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
        return $this->storage->get($key, $default);
    }

    /**
     * Set a value for a session key. This method stores the key-value pair in the session.
     *
     * @param string $key   The key to set.
     * @param mixed  $value The value to associate with the key.
     */
    public function set(string $key, mixed $value)
    {
        $this->storage->set($key, $value);
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
        return $this->storage->has($key);
    }

    /**
     * Remove a specific key and its associated value from the session.
     *
     * @param string $key The key to remove.
     */
    public function remove(string $key)
    {
        $this->storage->remove($key);
    }

    /**
     * Destroy the session, clearing all stored data and ending the session.
     */
    public function destroy()
    {
        $this->storage->destroy();
    }
}
