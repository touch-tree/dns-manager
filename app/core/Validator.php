<?php

namespace App\Core;

use Exception;

/**
 * The Validator class provides a simple and extensible way to validate data based on specified rules.
 *
 * @package App\Core
 */
class Validator
{
    /**
     * The data to be validated.
     *
     * @var array
     */
    protected array $data;

    /**
     * The validation rules for each field.
     *
     * @var array
     */
    protected array $rules;

    /**
     * The array to store validation errors.
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * Validator constructor.
     *
     * @param array $data The data to be validated.
     * @param array $rules An associative array where keys are parameter names and values are validation patterns (e.g. ['name' => 'required|string|max:255']).
     */
    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * Validates the data based on the specified rules.
     *
     * @return bool True if validation passes, false otherwise.
     * @throws Exception If an unsupported validation rule is encountered.
     */
    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            array_map(fn($rule) => $this->apply_rule($field, $rule), explode('|', $rules));
        }

        return empty($this->errors);
    }

    /**
     * Applies a single validation rule to a field.
     *
     * @param string $field The field to validate.
     * @param string $rule The validation rule to apply.
     * @throws Exception If an unsupported validation rule is encountered.
     */
    protected function apply_rule(string $field, string $rule)
    {
        $rule_parts = explode(':', $rule, 2);
        $parameters = isset($rule_parts[1]) ? explode(',', $rule_parts[1]) : [];

        switch (strtolower($rule_parts[0])) {
            case 'required':
                $this->validate_required($field);
                break;
            case 'string':
                $this->validate_string($field);
                break;
            case 'numeric':
                $this->validate_numeric($field);
                break;
            case 'email':
                $this->validate_email($field);
                break;
            case 'max':
                $this->validate_max($field, $parameters);
                break;
            default:
                throw new Exception('Validation rule ' . $rule . ' not supported');
        }
    }

    /**
     * Adds a validation error for a specific field and rule.
     *
     * @param string $field The field for which the validation error occurred.
     * @param string $rule The rule that was not satisfied.
     */
    protected function add_error(string $field, string $rule)
    {
        if (isset($this->errors[$field]) === false) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $rule;
    }

    /**
     * Retrieves the array of validation errors.
     *
     * @return array|null The array of validation errors, or null if no errors exist.
     */
    public function errors(): ?array
    {
        return empty($this->errors) ? null : $this->errors;
    }

    /**
     * Validates that the specified field is required and not empty.
     *
     * @param string $field The field to validate.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function validate_required(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = empty($value) === false;

        if ($is_valid === false) {
            $this->add_error($field, 'required');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is a string.
     *
     * @param string $field The field to validate.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function validate_string(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && is_string($value);

        if ($is_valid === false) {
            $this->add_error($field, 'string');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is numeric.
     *
     * @param string $field The field to validate.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function validate_numeric(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && is_numeric($value);

        if (!$is_valid) {
            $this->add_error($field, 'numeric');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is a valid email address.
     *
     * @param string $field The field to validate.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function validate_email(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

        if (!$is_valid) {
            $this->add_error($field, 'email');
        }

        return $is_valid;
    }

    /**
     * Validates that the length of the specified field does not exceed the specified maximum value.
     *
     * @param string $field The field to validate.
     * @param array $parameters An array of parameters, with the first parameter representing the maximum length.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function validate_max(string $field, array $parameters): bool
    {
        $value = $this->data[$field] ?? null;

        if (isset($value) && strlen($value) > (isset($parameters[0]) ? (int)$parameters[0] : 0)) {
            $this->add_error($field, 'max');
            return false;
        }

        return true;
    }
}
