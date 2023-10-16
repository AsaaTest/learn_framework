<?php

namespace Learn\Validation\Rules;

/**
 * LessThan Validation Rule
 *
 * This class represents a validation rule for checking if a given field's value is a numeric value less than a specified threshold.
 */
class LessThan implements ValidationRule
{
    /**
     * The maximum value that the field's value should be less than.
     *
     * @var float
     */
    private float $lessThan;

    /**
     * Create a new LessThan validation rule instance.
     *
     * @param float $lessThan The maximum value that the field's value should be less than.
     */
    public function __construct(float $lessThan)
    {
        $this->lessThan = $lessThan;
    }

    /**
     * Get the validation error message.
     *
     * @return string The error message to display when validation fails.
     */
    public function message(): string
    {
        return "The :attribute must be a numeric value less than {$this->lessThan}.";
    }

    /**
     * Check if the given field's value is a numeric value less than the specified threshold.
     *
     * @param string $field The name of the field being validated.
     * @param array $data An array of input data to validate.
     * @return bool True if the field's value is less than the specified threshold, false otherwise.
     */
    public function isValid(string $field, array $data): bool
    {
        // Check if the field exists in the input data and is a numeric value less than $this->lessThan.
        return isset($data[$field]) && is_numeric($data[$field]) && $data[$field] < $this->lessThan;
    }
}
