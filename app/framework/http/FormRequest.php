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
     * Validates multiple parameters based on specified rules and manages error handling.
     *
     * If no custom rules are provided, the default rules set in the class will be used.
     * The validation errors are stored in the session's 'errors' key, and the input data
     * is flashed to the session for convenient retrieval in subsequent requests.
     *
     * @param array|null $rules Set options to overwrite set options of custom rules (Optional)
     * @return Validator
     *
     * @throws Exception
     */
    final public function validate(?array $rules = []): Validator
    {
        $validator = parent::validate(empty($rules) ? $this->rules() : []);
        $errors = $validator->errors();

        if (is_null($errors)) {
            session()->forget(['errors', 'flash']);
        }

        session()->put('errors', $errors);

        return $validator;
    }
}