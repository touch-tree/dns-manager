<?php

/*
|-----------------------------------------------------------------------------
| Application Functions
|-----------------------------------------------------------------------------
|
| This file contains commonly used functions in the application. These
| functions provide utility for our Framework.
|
|-----------------------------------------------------------------------------
*/

use Framework\Foundation\App;
use Framework\Foundation\Config;
use Framework\Foundation\Container;
use Framework\Foundation\ParameterBag;
use Framework\Foundation\Session;
use Framework\Foundation\View;
use Framework\Http\HeaderBag;
use Framework\Http\Redirect;
use Framework\Http\RedirectResponse;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Server;
use Framework\Routing\Router;

/**
 * Redirect to a specified route.
 *
 * @param string|null $route The route to redirect to. If null, path of redirect should be set using 'route' method instead.
 * @return RedirectResponse A RedirectResponse instance representing the redirection.
 */
function redirect(string $route = null): RedirectResponse
{
    return app(Redirect::class)->to($route);
}

/**
 * Helper function to create an instance of the Response class.
 *
 * @param mixed $content The content of the response.
 * @param int $status_code The HTTP status code of the response.
 * @param HeaderBag|null $headers The HeaderBag instance containing HTTP headers.
 * @return Response
 */
function response($content = null, int $status_code = Response::HTTP_OK, HeaderBag $headers = null): Response
{
    return new Response($content, $status_code, $headers ?: new HeaderBag());
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
 * Get path to 'resources' directory.
 *
 * @param string $path
 * @return false|string
 */
function resource_path(string $path)
{
    return realpath(base_path('resources/' . ltrim($path)));
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
function session(string $key = null, $value = null)
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
 * Get the error associated with the given key from the session.
 *
 * @param string $key The key to retrieve the error.
 * @return string|null The error message or null if not found.
 */
function error(string $key): ?string
{
    $array = (array)session()->get('errors.form.' . $key, []);

    return reset($array) ?? null;
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
 * Get server.
 *
 * @return Server
 */
function server(): Server
{
    return app(Server::class);
}

/**
 * Get the current request instance.
 *
 * This function provides a convenient way to obtain the current request object
 * throughout the application. It ensures that only a single instance of the
 * Request class is created and reused.
 *
 * @return Request The instance of the Request class.
 */
function request(): Request
{
    static $request;

    if (is_null($request)) {
        $request = app(Request::class);
    }

    return $request;
}

/**
 * Retrieve the previous input value for a given key from the session.
 *
 * This function is commonly used in the context of form submissions
 * where validation fails, and you need to repopulate form fields
 * with the previously submitted values.
 *
 * @param string $key The key for which the previous input value should be retrieved.
 * @param string|null $default The default value if the previous input value cannot be retrieved.
 * @return mixed Returns the previous input value for the specified key or null if not found.
 */
function old(string $key, ?string $default = null)
{
    return request()->old($key) ?? $default;
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
 * @param mixed|null $message The variable or message to be dumped.
 * @return void
 */
function dd(...$message)
{
    if (!empty($message)) {
        var_dump($message);
    }

    die();
}

/**
 * Get an instance of the specified class from the Container class.
 *
 * This function acts as a convenient entry point to retrieve instances of
 * classes from the application's Dependency Injection (DI) Container.
 *
 * @template T
 * @param class-string<T>|null $class_name The fully qualified class name to resolve.
 * @return T|App|null An instance of the specified class, or null if the instance cannot be resolved.
 *
 * @throws Error If there is an error during the resolution process.
 * @see Container
 */
function app(string $class_name = null)
{
    try {
        return Container::get($class_name ?: App::class);
    } catch (ReflectionException|Exception $exception) {
        return null;
    }
}

/**
 * Get the absolute path to the Base directory of the application.
 *
 * @param string|null $path The relative path to append to the Base path (optional).
 * @return string The absolute path to the Base directory of the application.
 */
function base_path(string $path = null): string
{
    $directory = dirname(__DIR__);

    if ($path) {
        $directory .= DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);
    }

    return $directory;
}

/**
 * Get the Base URL of the application.
 *
 * @return string The Base URL.
 */
function base_url(): string
{
    return ($_SERVER['REQUEST_SCHEME'] ?? 'http') . ':' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_ADDR']) . DIRECTORY_SEPARATOR;
}

/**
 * Generate a redirect response back to the previous page.
 *
 * This function creates a redirect response to the URL specified in the 'Referer' header
 * or defaults to the home URL if the 'Referer' header is not present. It is a shorthand
 * for `redirect()->back()`.
 *
 * @return RedirectResponse
 *
 * @see Redirect::back()
 * @see redirect()
 */
function back(): RedirectResponse
{
    return app(Redirect::class)->back();
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
    return config('url', base_url()) . '/public/' . trim($path, DIRECTORY_SEPARATOR);
}

/**
 * Search for an object in an array based on specified properties and values.
 *
 * @param array $array The array to search.
 * @param array $search The associative array of properties and values to match.
 *
 * @return array|null The found object or null if not found.
 */
function find_object_by_properties(array $array, array $search): ?array
{
    foreach ($array as $item) {
        if (is_array($item) && array_intersect_assoc($search, $item) == $search) {
            return $item;
        }
    }

    return null;
}

/**
 * Add an error message to the 'errors' session.
 *
 * These errors are accessible in the next request and in views through the 'errors' variable.
 * Errors added through this function are distinct from form validation errors and won't be accessed via the 'error' function in views.
 *
 * @param string $key The key associated with the custom error.
 * @param string $value The custom error message to be added.
 * @return void
 *
 * @see error()
 */
function add_error(string $key, string $value): void
{
    session()->put('errors.errors', array_merge(session()->get('errors.errors', []), [$key => $value]));
}

/**
 * Get errors from 'errors' session.
 *
 * @return ParameterBag
 *
 * @see add_error()
 * @see error()
 */
function retrieve_error_bag(): ParameterBag
{
    return new ParameterBag(session()->get('errors.errors', []));
}