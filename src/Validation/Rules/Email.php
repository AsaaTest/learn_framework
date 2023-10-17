<?php

namespace Learn\Validation\Rules;

/**
 * Email Validation Rule
 *
 * This class represents a validation rule for checking if a field contains a valid email address.
 */
class Email implements ValidationRule
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
        return "The field {$this->attribute} must be a valid email address.";
    }

    /**
     * Check if the given field's value is a valid email address.
     *
     * @param string $field The name of the field being validated.
     * @param array $data An array of input data to validate.
     * @return bool True if the field is a valid email address, false otherwise.
     */
    public function isValid(string $field, array $data): bool
    {
        $this->attribute = $field;
        if(!array_key_exists($field, $data)) {
            return false;
        }
        $email = strtolower(trim($data[$field]));
        $split = explode("@", $email);

        // Check if the email address contains a single "@" character.
        if (count($split) != 2) {
            return false;
        }
        [$username, $domain] = $split;

        $split = explode(".", $domain);

        // Check if the domain part contains a single "." character.
        if (count($split) != 2) {
            return false;
        }
        [$label, $topLevelDomain] = $split;

        // Check the lengths of username, label, and top-level domain.
        return strlen($username) >= 1 && strlen($label) >= 1 && strlen($topLevelDomain) >= 1;
    }
}
