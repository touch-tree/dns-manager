<?php

/*
|-----------------------------------------------------------------------------
| Application Functions
|-----------------------------------------------------------------------------
|
| This file contains functions commonly used in the application, such as
| redirecting and rendering views. It defines the `redirect` and `view`
| functions, creating instances of the Redirect and View core.
|
|-----------------------------------------------------------------------------
*/

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
 * Generate a URL for the given path and parameters.
 *
 * @param string $path The path for the URL.
 * @param array $parameters Associative array of parameters to include in the URL query string.
 * @return string The generated URL.
 */
function url(string $path, array $parameters = []): string
{
    $url = (($_SERVER['HTTPS'] ?? 'off') === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' . ltrim($path, '/');

    if (!empty($parameters)) {
        $url .= '?' . http_build_query($parameters);
    }

    return $url;
}

/**
 * Generates the URL for an asset based on the provided relative path.
 *
 * @param string $path The relative path to the asset.
 * @return string The full URL for the asset.
 */
function asset(string $path): string
{
    return url('/resources/') . $path;
}

/**
 * Retrieves the content of the header template file.
 *
 * @return string|null The content of the header template file if it exists, or null otherwise.
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
 * Dump and die function for quick debugging.
 *
 * @param mixed $message The variable or message to be dumped.
 * @return void
 */
function dd($message)
{
    var_dump($message);
    die();
}

/**
 * Get an instance of the specified class from the service container.
 *
 * @param string $class_name The fully qualified class name to resolve.
 * @return object An instance of the specified class.
 * @throws ReflectionException If the class cannot be reflected.
 */
function app(string $class_name): object
{
    return ServiceProvider::get($class_name);
}