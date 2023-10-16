<?php

namespace Learn\Validation;

use Learn\Validation\Exceptions\RuleParseException;
use Learn\Validation\Exceptions\UnknowRuleException;
use Learn\Validation\Rules\Email;
use Learn\Validation\Rules\LessThan;
use Learn\Validation\Rules\Number;
use Learn\Validation\Rules\Required;
use Learn\Validation\Rules\RequiredWhen;
use Learn\Validation\Rules\RequiredWith;
use Learn\Validation\Rules\ValidationRule;
use ReflectionClass;

/**
 * Rule Class
 *
 * This class provides convenient methods for creating instances of validation rules. Validation rules define the
 * criteria for validating input data, such as checking for required fields, email formats, numerical values, etc.
 */
class Rule
{
    private static array $rules = [];

    private static array $defaultRules = [
        Required::class,
        RequiredWith::class,
        RequiredWhen::class,
        Number::class,
        LessThan::class,
        Email::class
    ];

    /**
     * Load the default validation rules.
     *
     * This method loads the default validation rules defined in the class.
     */
    public static function loadDefaultRules()
    {
        self::load(self::$defaultRules);
    }

    /**
     * Load custom validation rules.
     *
     * @param array $rules An array of custom validation rules to load.
     */
    public static function load(array $rules)
    {
        foreach ($rules as $class) {
            $className = array_slice(explode("\\", $class), -1)[0];
            $ruleName = snake_case($className);
            self::$rules[$ruleName] = $class;
        }
    }

    /**
     * Get the snake_cased name of a validation rule.
     *
     * @param ValidationRule $rule The validation rule for which to get the name.
     *
     * @return string The snake_cased name of the validation rule.
     */
    public static function nameOf(ValidationRule $rule): string
    {
        $class = new ReflectionClass($rule);
        return snake_case($class->getShortName());
    }

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
     *
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
     *
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
     *
     * @return ValidationRule An instance of the RequiredWhen validation rule.
     */
    public static function requiredWhen(string $otherField, string $operator, int|float $value): ValidationRule
    {
        return new RequiredWhen($otherField, $operator, $value);
    }

    /**
     * Parse a basic rule without parameters.
     *
     * @param string $ruleName The name of the rule to parse.
     *
     * @return ValidationRule An instance of the parsed rule.
     *
     * @throws RuleParseException If the rule requires parameters but none were passed.
     */
    public static function parseBasicRule(string $ruleName): ValidationRule
    {
        $class = new ReflectionClass(self::$rules[$ruleName]);
        if (count($class->getConstructor() ?->getParameters() ?? []) > 0) {
            throw new RuleParseException("Rule $ruleName requires parameters, but none have been passed");
        }
        return $class->newInstance();
    }

    /**
     * Parse a rule with parameters.
     *
     * @param string $ruleName The name of the rule to parse.
     * @param string $params The parameters to pass to the rule.
     *
     * @return ValidationRule An instance of the parsed rule with parameters.
     *
     * @throws RuleParseException If the rule requires a different number of parameters than provided.
     */
    public static function parseRuleWithParameters(string $ruleName, string $params): ValidationRule
    {
        $class = new ReflectionClass(self::$rules[$ruleName]);
        $constructorParameters = $class->getConstructor() ?->getParameters() ?? [];
        $givenParameters = array_filter(explode(",", $params), fn ($p) => !empty($p));

        if (count($givenParameters) !== count($constructorParameters)) {
            throw new RuleParseException(sprintf(
                "Rule %s requires %d parameters, but %d were given: %s",
                $ruleName,
                count($constructorParameters),
                count($givenParameters),
                $params
            ));
        }

        return $class->newInstance(...$givenParameters);
    }

    /**
     * Parse a rule from a string.
     *
     * @param string $str The string to parse as a validation rule.
     *
     * @return ValidationRule An instance of the parsed validation rule.
     *
     * @throws RuleParseException If the string is empty or cannot be parsed as a rule.
     * @throws UnknowRuleException If the specified rule is not recognized.
     */
    public static function from(string $str): ValidationRule
    {
        if (strlen($str) == 0) {
            throw new RuleParseException("Can't parse an empty string to a rule");
        }

        $ruleParts = explode(":", $str);

        if (!array_key_exists($ruleParts[0], self::$rules)) {
            throw new UnknowRuleException("Rule {$ruleParts[0]} not found");
        }

        if (count($ruleParts) == 1) {
            return self::parseBasicRule($ruleParts[0]);
        }

        [$ruleName, $params] = $ruleParts;

        return self::parseRuleWithParameters($ruleName, $params);
    }
}
