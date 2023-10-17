<?php

namespace Learn\Validation\Rules;

/**
 * Required Validation Rule
 *
 * This class represents a validation rule for checking if a given field is required and must not be empty.
 */
class Required implements ValidationRule
{
    /**
     * name attribute for message
     */
    public string $attribute;

    /**
     * Get the validation error message.
     *
     * @return string The error message to display when validation fails.
     */
    public function message(): string
    {
        return "The field {$this->attribute} is required.";
    }

    /**
     * Check if the given field is required and not empty.
     *
     * @param string $field The name of the field being validated.
     * @param array $data An array of input data to validate.
     * @return bool True if the field is required and not empty, false otherwise.
     */
    public function isValid(string $field, array $data): bool
    {
        $this->attribute = $field;
        // Check if the field exists in the input data and if its value is not empty (not equal to an empty string).
        return isset($data[$field]) && $data[$field] !== '';
    }
}
