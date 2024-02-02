<?php

namespace App\Core;

use Error;
use LogicException;

/**
 * This class provides functionality for creating redirects with flash data.
 *
 * @package App\Core
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
     * @throws Error If the provided route is invalid.
     */
    public function __construct(Session $session, ?string $path = null)
    {
        $this->session = $session;

        if ($path !== null) {
            $route = route($path);

            if ($route === null) {
                throw new Error('Route is invalid: ' . $path);
            }

            $this->path = $route;
        }
    }

    /**
     * Create a new Redirect instance for the specified route.
     *
     * @param string $path The destination path for the redirect.
     * @return Redirect
     */
    public function route(string $path): Redirect
    {
        $route = route($path);

        if ($route === null) {
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
     * @throws LogicException If the 'path' property is not set.
     */
    public function with(string $key, $value): Redirect
    {
        if ($this->path === null) {
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
