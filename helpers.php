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

use App\Framework\Base\Config;
use App\Framework\Base\Container;
use App\Framework\Base\Session;
use App\Framework\Base\View;
use App\Framework\Http\Redirect;
use App\Framework\Http\Request;
use App\Framework\Routing\Router;

/**
 * Redirect to a specified route.
 *
 * @param string|null $route The route to redirect to.
 * @return Redirect
 */
function redirect(?string $route = null): Redirect
{
    return new Redirect(session(), $route);
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
 * @template T
 * @param string|null $key The key of the session value.
 * @param T|null $value The value to set for the session key.
 * @return Session|T|string|null
 */
function session(?string $key = null, $value = null)
{
    static $session;

    if (is_null($session)) {
        $session = new Session();
    }

    if (!is_null($key) && !is_null($value)) {
        $session->put($key, $value);
    }

    if (!is_null($key)) {
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
    return Router::route($name, $parameters);
}

/**
 * Get the current request instance.
 *
 * This function provides a convenient way to obtain the current request object
 * throughout the application. It ensures that only a single instance of the
 * Request class is created and reused, promoting efficiency.
 *
 * @return Request The instance of the Request class.
 */
function request(): Request
{
    static $request;

    if (is_null($request)) {
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
 * @template T
 * @param string|array<T>|null $key The configuration key or an array of key-value pairs to set (optional).
 * @param T $default The default value to return if the key is not found (optional).
 * @return T|array The value of the configuration key, the entire configuration array, or the default value.
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
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
function dd($message)
{
    var_dump($message);
    die();
}

/**
 * Get an instance of the specified class from the service container.
 *
 * @template T
 * @param class-string<T> $class_name The fully qualified class name to resolve.
 * @return T|null An instance of the specified class, or false if instance cannot be resolved.
 * @throws Error
 */
function app(string $class_name)
{
    try {
        return Container::get($class_name);
    } catch (ReflectionException|Exception $exception) {
        return null;
    }
}

/**
 * Get the absolute path to the base directory of the application.
 *
 * @param string|null $path The relative path to append to the base path (optional).
 * @return string The absolute path to the base directory of the application.
 */
function base_path(?string $path = null): string
{
    $_path = __DIR__;

    if (!is_null($path)) {
        $_path .= DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);
    }

    return $_path;
}

/**
 * Get the base URL of the application.
 *
 * @return string The base URL.
 */
function base_url(): string
{
    return ($_SERVER['REQUEST_SCHEME'] ?? 'http') . PATH_SEPARATOR . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_ADDR']) . DIRECTORY_SEPARATOR;
}

/**
 * Generate a URL based on the given route.
 *
 * @param string|null $path The path for the URL.
 *
 * @return string The generated URL.
 */
function url(?string $path): string
{
    return base_url() . ltrim($path, DIRECTORY_SEPARATOR);
}

/**
 * Generates the URL for an asset based on the provided relative path.
 *
 * @param string $path The relative path to the asset.
 * @return string The full URL for the asset.
 */
function asset(string $path): string
{
    $base_url = base_url();
    $url = config('url', $base_url);

    return $url . '/public/' . trim($path, DIRECTORY_SEPARATOR);
}

/**
 * Retrieves the content of the specified template file by filename.
 *
 * @param string $file The relative file path of the template file.
 * @return string|null The content of the template file if it exists, or null otherwise.
 * @throws Error
 */
function get_template(string $file): ?string
{
    $path = base_path('/public/templates/' . $file . '.php');

    if (!file_exists($path)) {
        throw new Error('Unable to find template: ' . $file);
    }

    ob_start();
    include $path;
    return ob_get_clean();
}