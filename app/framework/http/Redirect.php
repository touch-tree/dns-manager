<?php

namespace App\Framework\Http;

use App\Framework\Base\Session;
use Error;
use Exception;
use LogicException;

/**
 * This class provides functionality for creating redirects with flash data.
 *
 * @package App\Framework\Http
 */
class Redirect
{
    /**
     * The destination path for the redirect.
     *
     * @var string|null
     */
    protected ?string $path;

    /**
     * The session manager for storing flash data.
     *
     * @var Session
     */
    protected Session $session;

    /**
     * Redirect constructor.
     *
     * @param Session $session The session manager for storing flash data.
     * @param string|null $path The destination path for the redirect.
     *
     * @throws Error If the provided route is invalid.
     */
    public function __construct(Session $session, ?string $path = null)
    {
        $this->session = $session;

        if (is_string($path)) {
            $this->route($path);
        }
    }

    /**
     * Create a new Redirect instance for the specified route.
     *
     * @param string $path The destination path for the redirect.
     * @return Redirect
     *
     * @throws Error If the provided route is invalid.
     */
    public function route(string $path): Redirect
    {
        $route = route($path);

        if (is_null($route)) {
            throw new Error('Route is invalid: ' . $path);
        }

        $this->path = $route;

        return $this;
    }

    /**
     * Attach flash data to the redirect.
     *
     * @param string $key The key for the flash data.
     * @param mixed $value The value of the flash data.
     * @return Redirect
     *
     * @throws LogicException If the 'path' property is not set.
     */
    public function with(string $key, $value): Redirect
    {
        if (is_null($this->path)) {
            throw new LogicException('Cannot use with method without setting the path property');
        }

        $this->session->flash($key, $value);

        return $this;
    }

    /**
     * Perform the actual redirection and terminate the script.
     *
     * @return void
     */
    public function send()
    {
        header('Location: ' . $this->path);
        exit();
    }
}
