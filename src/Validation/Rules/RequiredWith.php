<?php

namespace Learn\Validation\Rules;

/**
 * RequiredWith Validation Rule
 *
 * This class represents a validation rule for checking if a field is required when another field has a specific value.
 */
class RequiredWith implements ValidationRule
{
    /**
     * name attribute for message
     */
    public string $attribute;

    /**
     * The name of the related field that triggers the requirement of this field.
     *
     * @var string
     */
    protected string $withField;

    /**
     * Create a new RequiredWith instance.
     *
     * @param string $withField The name of the related field.
     */
    public function __construct(string $withField)
    {
        // Assign the provided field name to the class property.
        $this->withField = $withField;
    }

    /**
     * Get the validation error message.
     *
     * @return string The error message to display when validation fails.
     */
    public function message(): string
    {
        return "The field {$this->attribute} is required when {$this->withField} exist.";
    }

    /**
     * Check if the field is required when the related field has a specific value.
     *
     * @param string $field The name of the field being validated.
     * @param array $data An array of input data to validate.
     * @return bool True if the field is required when the related field has a value, false otherwise.
     */
    public function isValid(string $field, array $data): bool
    {
        $this->attribute = $field;
        // Check if the related field exists and is not empty.
        if (isset($data[$this->withField]) && $data[$this->withField] !== "") {
            // The field is required if it exists and is not empty.
            return isset($data[$field]) && $data[$field] !== "";
        }

        // If the related field is empty, the field is not required.
        return true;
    }
}
