<?php

namespace Learn\Validation\Exceptions;

use Learn\Exceptions\LearnException;

/**
 * ValidationException class represents an exception thrown when validation fails.
 *
 * This exception is used to handle and report validation errors that occur during the validation process.
 * It extends the LearnException class and provides a convenient way to access the validation error messages.
 *
 * @package Learn\Validation\Exceptions
 */
class ValidationException extends LearnException
{
    /**
     * The array of validation errors.
     *
     * @var array
     */
    protected array $errors;

    /**
     * Create a new ValidationException instance.
     *
     * @param array $errors An array of validation errors.
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Get the validation errors.
     *
     * @return array An array of validation errors with field names and error messages.
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
