<?php

namespace App\Framework\Http;

use App\Framework\Foundation\Session;
use App\Framework\Foundation\Validator;
use Exception;

/**
 * The Request class represents an HTTP request entity and provides methods
 * to work with query parameters. It is designed to simplify the extraction
 * of query parameters from a URL.
 *
 * @package App\Framework\Http
 */
class Request
{
    /**
     * Server instance.
     *
     * @var Server
     */
    private Server $server;

    /**
     * Header instance.
     *
     * @var HeaderBag
     */
    public HeaderBag $headers;

    /**
     * Request constructor.
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get the HTTP method of the request.
     *
     * @return string The HTTP method (GET, POST, PUT, DELETE, etc.).
     */
    public function method(): string
    {
        return strtoupper($this->server->get('REQUEST_METHOD'));
    }

    /**
     * Get the full request URI including query parameters.
     *
     * @return mixed|string|null The full request URI.
     */
    public function request_uri()
    {
        return $this->server->get('REQUEST_URI');
    }

    /**
     * Get the path component of the request URI.
     *
     * @return array|false|int|string|null The path component of the request URI.
     */
    public function path()
    {
        return parse_url($this->request_uri(), PHP_URL_PATH);
    }

    /**
     * Get the HTTP headers from the request.
     *
     * @return HeaderBag The HTTP headers.
     */
    public function headers(): HeaderBag
    {
        if (!isset($this->headers)) {
            $this->headers = new HeaderBag(array_change_key_case(getallheaders()));
        }

        return $this->headers;
    }

    /**
     * Retrieve the value of a query parameter.
     *
     * @param string $parameter The name of the query parameter.
     * @param string|null $default The default value if the parameter is not set.
     * @return string|null The value of the query parameter or the default value.
     */
    public function get(string $parameter, ?string $default = null): ?string
    {
        return $_GET[$parameter] ?? $default;
    }

    /**
     * Retrieve the value of a form post data parameter.
     *
     * @param string $parameter The name of the form post data parameter.
     * @param string|null $default The default value if the parameter is not set.
     * @return string|null The value of the form post data parameter or the default value.
     */
    public function input(string $parameter, ?string $default = null): ?string
    {
        return $_POST[$parameter] ?? $default;
    }

    /**
     * Retrieve all form post data as an associative array.
     *
     * @return array The associative array of form post data.
     */
    public function all(): array
    {
        return $_POST;
    }

    /**
     * Validate multiple parameters based on the given validation patterns.
     *
     * @param array $rules An associative array where keys are parameter names and values are validation patterns e.g 'name' as 'required|string|max:255'.
     * @return Validator The Validator instance.
     *
     * @throws Exception
     */
    public function validate(array $rules): Validator
    {
        $validator = new Validator($this->all(), $rules);

        $validator->validate();

        return $validator;
    }

    /**
     * Flash the current request data into the session and return the session instance.
     *
     * @return Session The session instance with flashed data.
     */
    public function flash(): Session
    {
        return $this->session()->flash('form', $_POST);
    }

    /**
     * Retrieve the old input data from the previous request.
     *
     * @param string $key The key to retrieve old input data.
     * @return mixed|null The old input data or null if not found.
     */
    public function old(string $key)
    {
        return $this->session()->get('flash')[$key] ?? null;
    }

    /**
     * Return the session object.
     *
     * @return Session The Session instance.
     */
    public function session(): Session
    {
        return session();
    }
}
