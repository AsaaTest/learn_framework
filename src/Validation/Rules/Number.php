<?php

namespace Learn\Validation\Rules;

/**
 * Number Validation Rule
 *
 * This class represents a validation rule for checking if a given field's value is a valid numeric value.
 */
class Number implements ValidationRule
{
    /**
     * Get the validation error message.
     *
     * @return string The error message to display when validation fails.
     */
    public function message(): string
    {
        return "The :attribute must be a valid numeric value.";
    }

    /**
     * Check if the given field's value is a valid numeric value.
     *
     * @param string $field The name of the field being validated.
     * @param array $data An array of input data to validate.
     * @return bool True if the field's value is a valid numeric value, false otherwise.
     */
    public function isValid(string $field, array $data): bool
    {
        // Check if the field exists in the input data and if its value is a valid numeric value.
        return isset($data[$field]) && is_numeric($data[$field]);
    }
}
