<?php

namespace Learn\Validation;

use Learn\Validation\Rules\Email;
use Learn\Validation\Rules\LessThan;
use Learn\Validation\Rules\Number;
use Learn\Validation\Rules\Required;
use Learn\Validation\Rules\RequiredWhen;
use Learn\Validation\Rules\RequiredWith;
use Learn\Validation\Rules\ValidationRule;

/**
 * Rule Class
 *
 * This class provides convenient methods for creating instances of validation rules. Validation rules define the
 * criteria for validating input data, such as checking for required fields, email formats, numerical values, etc.
 */
class Rule
{
    /**
     * Create a new Email validation rule instance.
     *
     * @return ValidationRule An instance of the Email validation rule.
     */
    public static function email(): ValidationRule
    {
        return new Email();
    }

    /**
     * Create a new Required validation rule instance.
     *
     * @return ValidationRule An instance of the Required validation rule.
     */
    public static function required(): ValidationRule
    {
        return new Required();
    }

    /**
     * Create a new RequiredWith validation rule instance.
     *
     * @param string $withField The name of the related field that triggers the requirement.
     * @return ValidationRule An instance of the RequiredWith validation rule.
     */
    public static function requiredWith(string $withField): ValidationRule
    {
        return new RequiredWith($withField);
    }

    /**
     * Create a new Number validation rule instance.
     *
     * @return ValidationRule An instance of the Number validation rule.
     */
    public static function number(): ValidationRule
    {
        return new Number();
    }

    /**
     * Create a new LessThan validation rule instance.
     *
     * @param float $value The numeric value that input should be less than.
     * @return ValidationRule An instance of the LessThan validation rule.
     */
    public static function lessThan(float $value): ValidationRule
    {
        return new LessThan($value);
    }

    /**
     * Create a new RequiredWhen validation rule instance.
     *
     * @param string $otherField The name of the other field to check against.
     * @param string $operator The comparison operator (e.g., "=", ">", "<", ">=", "<=").
     * @param int|float $value The value to compare with.
     * @return ValidationRule An instance of the RequiredWhen validation rule.
     */
    public static function requiredWhen(string $otherField, string $operator, int|float $value): ValidationRule
    {
        return new RequiredWhen($otherField, $operator, $value);
    }
}
