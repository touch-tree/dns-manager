<?php

namespace Framework\Foundation;

use Exception;

/**
 * The Validator class provides a simple and extensible way to validate data based on specified rules.
 *
 * @package Framework\Foundation
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
     * @var ParameterBag
     */
    protected ParameterBag $errors;

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

        $this->errors = new ParameterBag();
    }

    /**
     * Validates the data based on the specified rules.
     *
     * @return bool true if validation passes, false otherwise.
     *
     * @throws Exception If an unsupported validation rule is encountered.
     */
    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            array_map(fn($rule) => $this->apply_rule($field, $rule), explode('|', $rules));
        }

        return empty($this->errors->all());
    }

    /**
     * Applies a single validation rule to a field.
     *
     * @param string $field The field to validate.
     * @param string $rule The validation rule to apply.
     * @return bool true if the field passes the rule, false otherwise.
     *
     * @throws Exception If an unsupported validation rule is encountered.
     */
    protected function apply_rule(string $field, string $rule): bool
    {
        if ($rule === 'required') {
            return $this->is_required($field);
        }

        if ($rule === 'string') {
            return $this->is_string($field);
        }

        if ($rule === 'alpha') {
            return $this->is_alpha($field);
        }

        if ($rule === 'alpha_num') {
            return $this->is_alpha_num($field);
        }

        if ($rule === 'numeric') {
            return $this->is_numeric($field);
        }

        if ($rule === 'email') {
            return $this->is_email($field);
        }

        throw new Exception($rule);
    }

    /**
     * Adds a validation error for a specific field and rule.
     *
     * @param string $field The field for which the validation error occurred.
     * @param string $rule The rule that was not satisfied.
     */
    protected function add_error(string $field, string $rule)
    {
        $this->errors->set($field, $this->errors->get($field, [$rule]));
    }

    /**
     * Retrieves the validation errors.
     *
     * @return ParameterBag The collection of validation errors.
     */
    public function errors(): ParameterBag
    {
        return $this->errors;
    }

    /**
     * Validates that the specified field is required and not empty.
     *
     * @param string $field The field to validate.
     * @return bool true if the validation passes, false otherwise.
     */
    protected function is_required(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = !empty($value);

        if (!$is_valid) {
            $this->add_error($field, 'This field is required.');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field contains only letters (alphabetic characters).
     *
     * @param string $field The field to validate.
     * @return bool true if the validation passes, false otherwise.
     */
    protected function is_alpha(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && ctype_alpha($value);

        if (!$is_valid) {
            $this->add_error($field, 'This field must contain only alphabetic characters.');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field contains only alphanumeric characters.
     *
     * @param string $field The field to validate.
     * @return bool true if the validation passes, false otherwise.
     */
    protected function is_alpha_num(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && ctype_alnum($value);

        if (!$is_valid) {
            $this->add_error($field, 'This field must contain only alphanumeric characters.');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is a string.
     *
     * @param string $field The field to validate.
     * @return bool true if the validation passes, false otherwise.
     */
    protected function is_string(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && is_string($value);

        if (!$is_valid) {
            $this->add_error($field, 'This field must be a string.');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is numeric.
     *
     * @param string $field The field to validate.
     * @return bool true if the validation passes, false otherwise.
     */
    protected function is_numeric(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && is_numeric($value);

        if (!$is_valid) {
            $this->add_error($field, 'This field must contain only numeric characters.');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is a valid email address.
     *
     * @param string $field The field to validate.
     * @return bool true if the validation passes, false otherwise.
     */
    protected function is_email(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

        if (!$is_valid) {
            $this->add_error($field, 'This field must contain a valid email.');
        }

        return $is_valid;
    }
}
