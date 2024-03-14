<?php

namespace Framework\Http;

use Exception;
use Framework\Foundation\Session;
use Framework\Foundation\Validator;

/**
 * The Request class represents an HTTP request entity and provides methods to work with query parameters.
 *
 * This class provides methods to easier retrieve query parameters from a URL and provides
 * methods for handling form data, validation, and accessing request-related information.
 *
 * @package Framework\Http
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
     *
     * @param Server $server The Server instance.
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Retrieve the value of a query parameter.
     *
     * @param string $parameter The name of the query parameter.
     * @param string|null $default [optional] The default value if the parameter is not set.
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
     * @param string|null $default [optional] The default value if the parameter is not set.
     * @return string|null The value of the form post data parameter or the default value.
     */
    public function input(string $parameter, ?string $default = null): ?string
    {
        return $_POST[$parameter] ?? $default;
    }

    /**
     * Checks if the form input exists in the POST request.
     *
     * @param string $parameter The name of the form post data parameter.
     * @return bool
     */
    public function exists(string $parameter): bool
    {
        return isset($_POST[$parameter]);
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
     * @param array $rules An associative array where keys are parameter names and values are validation patterns (e.g. ['name' => 'required|string|max:255']).
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
        return $this->session()->get('flash.' . $key);
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
     * @return string|null The full request URI.
     */
    public function request_uri(): ?string
    {
        return $this->server->get('REQUEST_URI');
    }

    /**
     * Get the request scheme (HTTP or HTTPS).
     *
     * @return string The request scheme.
     */
    public function scheme(): string
    {
        return $this->server->get('REQUEST_SCHEME') ?? 'http';
    }

    /**
     * Get the HTTP host from the request headers. If not available, fallback to the server IP address.
     *
     * @return string The HTTP host or the server IP address.
     */
    public function host(): string
    {
        return $this->server->get('HTTP_HOST') ?? $this->server->get('SERVER_ADDR');
    }

    /**
     * Get the base URL of the current request.
     *
     * This method constructs and returns the base URL, including the scheme (HTTP or HTTPS)
     * and HTTP host, from the current request object, formatted as a URL.
     *
     * @return string The base URL formatted as a URL.
     */
    public function base_url(): string
    {
        return $this->scheme() . '://' . $this->host() . '/';
    }

    /**
     * Determine if the request is served over HTTPS.
     *
     * @return bool
     */
    public function is_secure(): bool
    {
        return $this->server->get('HTTPS') === 'on';
    }

    /**
     * Get the path component of the request URI.
     *
     * @return string|null The path component of the request URI.
     */
    public function path(): ?string
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
     * Return the session object.
     *
     * @return Session The Session instance.
     */
    public function session(): Session
    {
        return session();
    }
}