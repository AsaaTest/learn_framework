<?php

use Learn\Session\Session;

/**
 * Get the current session instance.
 *
 * @return Session The current session instance.
 */
function session(): Session
{
    return app()->session;
}

/**
 * Get the first error message for a given field.
 *
 * @param string $field The field for which you want to retrieve the error message.
 * @return string|null The first error message for the given field or null if not found.
 */
function error(string $field)
{
    $errors = session()->get('_errors', [])[$field] ?? [];

    $keys = array_keys($errors);

    if (count($keys) > 0) {
        return $errors[$keys[0]];
    }
    return null;
}

/**
 * Get the previous input value for a given field.
 *
 * @param string $field The field for which you want to retrieve the previous input value.
 * @return mixed|null The previous input value for the given field or null if not found.
 */
function old(string $field)
{
    return session()->get('_old', [])[$field] ?? null;
}
