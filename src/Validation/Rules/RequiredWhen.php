<?php

namespace Learn\Validation\Rules;

use Learn\Validation\Exceptions\RuleParseException;

/**
 * RequiredWhen Validation Rule
 *
 * This class represents a validation rule for checking if a field is required under certain conditions.
 */
class RequiredWhen implements ValidationRule
{
    /**
     * Create a new RequiredWhen instance.
     *
     * @param string $otherField The name of the other field to compare.
     * @param string $operator The comparison operator to use (e.g., '=', '>', '<', '>=', '<=').
     * @param string $compareWith The value to compare the other field against.
     */
    public function __construct(
        private string $otherField,
        private string $operator,
        private string $compareWith
    ) {
        // Assign constructor parameters to class properties.
        $this->otherField = $otherField;
        $this->operator = $operator;
        $this->compareWith = $compareWith;
    }

    /**
     * Get the validation error message.
     *
     * @return string The error message to display when validation fails.
     */
    public function message(): string
    {
        return "This field is required when $this->otherField $this->operator $this->compareWith";
    }

    /**
     * Check if the field is required based on the specified conditions.
     *
     * @param string $field The name of the field being validated.
     * @param array $data An array of input data to validate.
     * @return bool True if the field is required according to the conditions, false otherwise.
     */
    public function isValid($field, $data): bool
    {
        // Check if the other field exists in the input data.
        if (!array_key_exists($this->otherField, $data)) {
            return false;
        }

        // Determine if the field is required based on the specified comparison conditions.
        $isRequired = match ($this->operator) {
            "=" => $data[$this->otherField] == $this->compareWith,
            ">" => $data[$this->otherField] > floatval($this->compareWith),
            "<" => $data[$this->otherField] < floatval($this->compareWith),
            ">=" => $data[$this->otherField] >= floatval($this->compareWith),
            "<=" => $data[$this->otherField] <= floatval($this->compareWith),
            default => throw new RuleParseException("Unknow required_when operator: $this->operator")
        };

        // The field is required if the specified conditions are met, and it is not empty.
        return !$isRequired || isset($data[$field]) && $data[$field] != "";
    }
}
