<?php

namespace App\Framework\Support;

/**
 * This class is responsible for preparing a response that might contain errors to be returned alongside the data requested.
 *
 * @template T
 * @package App\Framework\Support
 */
class ErrorableBag
{
    /**
     * Errors.
     *
     * @var array
     */
    private array $errors;

    /**
     * Data.
     *
     * @var T
     */
    private $data;

    /**
     * Get errors.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Set errors.
     *
     * @param array $errors
     * @return $this
     */
    public function set_errors(array $errors): ErrorableBag
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Get data.
     *
     * @return T
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Set data.
     *
     * @param T $data
     * @return $this
     */
    public function set_data($data): ErrorableBag
    {
        $this->data = $data;

        return $this;
    }
}