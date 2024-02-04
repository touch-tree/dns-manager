<?php

/*
|-----------------------------------------------------------------------------
| Application Functions
|-----------------------------------------------------------------------------
|
| This file contains commonly used functions in the application. These
| functions provide utility for our framework.
|
|-----------------------------------------------------------------------------
*/

use App\Core\Config;
use App\Core\Redirect;
use App\Core\Request;
use App\Core\Route;
use App\Core\ServiceProvider;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;

/**
 * Redirect to a specified route.
 *
 * @param string|null $route The route to redirect to.
 * @return Redirect
 */
function redirect(?string $route = null): Redirect
{
    return new Redirect(new Session(), $route);
}

/**
 * Create a new View instance for rendering views.
 *
 * @param string $path The path to the view file.
 * @param array $data Data to pass to the view.
 * @return View
 */
function view(string $path, array $data = []): View
{
    return new View($path, $data);
}

/**
 * Get or set a session value.
 *
 * If both $key and $value are provided, it sets the session value.
 * If only $key is provided, it retrieves the session value.
 *
 * @param string|null $key The key of the session value.
 * @param mixed|null $value The value to set for the session key.
 * @return Session|string|null
 */
function session(?string $key = null, $value = null)
{
    static $session;

    if ($session === null) {
        $session = new Session();
    }

    if ($key !== null && $value !== null) {
        $session->put($key, $value);
    } elseif ($key !== null) {
        return $session->pull($key);
    }

    return $session;
}

/**
 * Get URL for a named route.
 *
 * @param string $name The name of the route.
 * @param array $parameters Associative array of route parameters.
 * @return string|null The URL for the named route with parameters applied.
 */
function route(string $name, array $parameters = []): ?string
{
    return Route::route($name, $parameters);
}

/**
 * Generates the URL for an asset based on the provided relative path.
 *
 * @param string $path The relative path to the asset.
 * @return string The full URL for the asset.
 */
function asset(string $path): string
{
    return '//' . $_SERVER['HTTP_HOST'] . '/' . ltrim($path, '/');
}

/**
 * Retrieves the content of the specified template file.
 *
 * @param string $file The relative file path of the template file.
 * @return string|null The content of the template file if it exists, or null otherwise.
 */
function get_template(string $file): ?string
{
    if (file_exists($path = ROOT_DIR . '/resources/templates/' . $file)) {
        ob_start();
        include $path;
        return ob_get_clean();
    }

    return null;
}

/**
 * Create and return a new Validator instance for the given rules and request data.
 *
 * @param array|string $rules The validation rules to apply.
 * @return Validator A new Validator instance.
 */
function validate(array $rules): Validator
{
    return new Validator(request()->all(), $rules);
}

/**
 * Get Request class.
 *
 * The Request class represents an HTTP request entity and provides methods
 * to work with query parameters. It is designed to simplify the extraction
 * of query parameters from a URL.
 *
 * This function ensures that only one instance of the Request class is created
 * and reused throughout the application.
 *
 * @return Request The instance of the Request class.
 */
function request(): Request
{
    static $request;

    if ($request === null) {
        $request = new Request();
    }

    return $request;
}

/**
 * Get the value of a configuration key or set a configuration value at runtime.
 *
 * If `$key` is `null`, it retrieves the entire configuration array. If `$key`
 * is an array, it sets multiple configuration values at once. If `$key` is a
 * string, it retrieves the value for the specified key.
 *
 * If `$key` is `null` and `$default` is provided, the default value will be
 * returned if the configuration key is not found.
 *
 * If `$key` is an array, it sets multiple configuration values at runtime and
 * returns the array of key-value pairs that were set.
 *
 * @param string|array|null $key The configuration key or an array of key-value pairs to set (optional).
 * @param mixed $default The default value to return if the key is not found (optional).
 * @return mixed|array The value of the configuration key, the entire configuration array, or the default value.
 */
function config($key = null, $default = null)
{
    if ($key === null) {
        return Config::all();
    }

    if (is_array($key)) {
        Config::set_many($key);
        return $key;
    }

    return Config::get($key, $default);
}

/**
 * Dump and die function for quick debugging.
 *
 * @param mixed $message The variable or message to be dumped.
 * @return void
 */
function dump($message)
{
    var_dump($message);
    die();
}

/**
 * Get an instance of the specified class from the service container.
 *
 * @template T
 * @param class-string<T> $class_name The fully qualified class name to resolve.
 * @return T An instance of the specified class.
 * @throws ReflectionException If the class cannot be reflected.
 */
function app(string $class_name)
{
    return ServiceProvider::get($class_name);
}
