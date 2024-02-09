<?php

namespace App\Framework\Http;

use App\Framework\Base\Validator;
use Exception;

/**
 * This class represents a form request entity and extends the base Request class.
 * It provides methods to handle and validate form data based on specified rules.
 * You can define custom validation rules within this class for specific use cases.
 *
 * @package App\Framework\Http
 */
class FormRequest extends Request
{
    /**
     * Get the validation rules for the form request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Validate multiple parameters based on the rules set for the request.
     * If no rules are provided the given validation rules will overwrite the set rules.
     *
     * @param array|null $rules Set options to overwrite set options of custom rules (Optional)
     * @return Validator
     *
     * @throws Exception
     */
    final public function validate(?array $rules = []): Validator
    {
        return parent::validate(empty($rules) ? $this->rules() : []);
    }
}