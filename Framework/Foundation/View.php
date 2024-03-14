<?php

namespace Framework\Foundation;

use Framework\Http\HeaderBag;
use Throwable;

/**
 * The View class is responsible for rendering the content of view files.
 *
 * This class provides a simple method to render views with optional data.
 *
 * @package Framework\Foundation
 */
class View
{
    /**
     * The path to the view file.
     *
     * @var string
     */
    protected string $path;

    /**
     * Data to be passed to the view.
     *
     * @var array
     */
    protected array $data;

    /**
     * Headers to be included in the response.
     *
     * @var HeaderBag
     */
    protected HeaderBag $headers;

    /**
     * View constructor.
     *
     * @param string $path The path to the view file.
     * @param array $data [optional] Data to be passed to the view.
     */
    public function __construct(string $path, array $data = [])
    {
        $this->data = $data;
        $this->path = $path;

        $this->headers = new HeaderBag();
    }

    /**
     * Create a new instance of the View class.
     *
     * @param string $path The path to the view file.
     * @param array $data [optional] Data to be passed to the view.
     * @return View
     */
    public static function make(string $path, array $data = []): View
    {
        return new self($path, $data);
    }

    /**
     * Add data to be passed to the view.
     *
     * @param string $key The key for the data.
     * @param mixed $value The value to be passed to the view.
     * @return View The current View instance.
     */
    public function with(string $key, $value): View
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Set a header for the response.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     * @return View The current View instance.
     */
    public function with_header(string $name, string $value): View
    {
        $this->headers->set($name, $value);

        return $this;
    }

    /**
     * Set headers for the response.
     *
     * @param HeaderBag $headers The headers for the response.
     * @return View The current View instance.
     */
    public function with_headers(HeaderBag $headers): View
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get the headers for the response.
     *
     * @return HeaderBag
     */
    public function get_headers(): HeaderBag
    {
        return $this->headers;
    }

    /**
     * Get the full path to the view file.
     *
     * @param string $path The path to the view file.
     * @return string|null The full path to the view file, or null if the file does not exist.
     */
    public static function file(string $path): ?string
    {
        $resolve_path = self::resolve_path($path);

        return file_exists($resolve_path) ? $resolve_path : null;
    }

    /**
     * Check if the view file exists.
     *
     * @param string $path The path to the view file.
     * @return bool True if the view file exists, otherwise false.
     */
    public static function exists(string $path): bool
    {
        return !is_null(self::file($path));
    }

    /**
     * Get the path to the view file.
     *
     * @param string $path The path to the view file.
     * @return string|null The path to the view file, or null if the file does not exist.
     */
    public static function path(string $path): ?string
    {
        return self::resolve_path($path);
    }

    /**
     * Resolve the path to the view file.
     *
     * @param string $path The path to the view file.
     * @return string|null The resolved path to the view file, or null if the file does not exist.
     */
    private static function resolve_path(string $path): ?string
    {
        $path = realpath($path) ?: realpath(resource_path('views/' . str_replace('.', '/', $path) . '.php'));

        return $path ?: null;
    }

    /**
     * Render the view and return the content as a string.
     *
     * @return string|null The rendered view content, or null on failure.
     */
    public function render(): ?string
    {
        $path = self::file($this->path);

        if (is_null($path)) {
            return null;
        }

        try {
            extract($this->data);
            ob_start();

            include $path;

            return ob_get_clean() ?: null;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
}
