<?php

namespace Learn\Validation;

use Learn\Validation\Exceptions\ValidationException;

/**
 * Validator for input data.
 *
 * The `Validator` class is responsible for validating input data based on defined rules. It checks each field's
 * values against one or more validation rules and collects any validation errors. If any validation errors occur,
 * it throws a `ValidationException` with the error details. Otherwise, it returns the validated data.
 */
class Validator
{
    /**
     * The input data to be validated.
     *
     * @var array
     */
    protected array $data;

    /**
     * Create a new instance of the validator with the provided input data.
     *
     * @param array $data The input data to be validated.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate the input data against defined validation rules.
     *
     * @param array $validationRules An array of validation rules associated with input fields.
     * @param array $messages Custom error messages for validation rules (optional).
     *
     * @return array An array of validated data if validation is successful.
     *
     * @throws ValidationException If validation errors occur, this exception is thrown, containing error details.
     */
    public function validate(array $validationRules, array $messages = []): array
    {
        $validated = [];
        $errors = [];

        // Iterate through each field and its associated rules for validation.
        foreach ($validationRules as $field => $rules) {
            if (!is_array($rules)) {
                $rules = [$rules];
            }

            $fieldUnderValidationErrors = [];

            // Validate the field against each of its rules.
            foreach ($rules as $rule) {
                if(is_string($rule)) {
                    $rule = Rule::from($rule);
                }
                if (!$rule->isValid($field, $this->data)) {
                    $message = $messages[$field][Rule::nameOf($rule)] ?? $rule->message();
                    $fieldUnderValidationErrors[Rule::nameOf($rule)] = $message;
                }
            }

            // If there are validation errors for the field, store them.
            if (count($fieldUnderValidationErrors) > 0) {
                $errors[$field] = $fieldUnderValidationErrors;
            } else {
                // The field has passed all validation rules; store it in the validated data.
                $validated[$field] = $this->data[$field] ?? null;
            }
        }

        // If there are validation errors, throw a `ValidationException` with error details.
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // Return the validated data.
        return $validated;
    }
}
