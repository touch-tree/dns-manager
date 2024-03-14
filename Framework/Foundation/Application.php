<?php

namespace Framework\Foundation;

use Exception;

/**
 * The Application class is responsible for bootstrapping the application and registering services.
 *
 * This class extends the Container class to provide dependency injection and service registration functionality.
 *
 * @package Framework\Foundation
 */
class Application extends Container
{
    /**
     * The array of registered service providers.
     *
     * @var array<ServiceProvider>
     */
    private static array $services = [];

    /**
     * The base path of this application.
     *
     * @var string
     */
    private static string $base_path;

    /**
     * Application constructor.
     *
     * @param string $base_path The base path for this application.
     */
    public function __construct(string $base_path)
    {
        self::$base_path = $base_path;

        parent::__construct();
    }

    /**
     * Get the absolute path to the base directory of the application.
     *
     * @param string|null $path [optional] The relative path to append to the base path.
     * @return string The absolute path to the base directory of the application.
     */
    public function base_path(?string $path = null): string
    {
        return self::$base_path . DIRECTORY_SEPARATOR . ltrim($path);
    }

    /**
     * Register services.
     *
     * @param array<string> $services An array of service provider class names.
     * @return void
     */
    public function register(array $services): void
    {
        self::$services = $services;

        foreach ($services as $service) {
            try {
                $class = parent::get($service);
            } catch (Exception $exception) {
                $class = null;
            }

            if (!is_a($class, ServiceProvider::class)) {
                continue;
            }

            $class->register(self::get_instance());
        }
    }

    /**
     * Get the registered services.
     *
     * @return array<ServiceProvider> An array of registered service provider instances.
     */
    public function get_services(): array
    {
        return self::$services;
    }
}
