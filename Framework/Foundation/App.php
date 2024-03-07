<?php

namespace Framework\Foundation;

/**
 * The Application class is responsible for bootstrapping our application and registering services.
 *
 * @package Framework\Foundation
 */
class App
{
    /**
     * Services.
     *
     * @var array<string>
     */
    private array $services = [];

    /**
     * Container instance.
     *
     * @var Container
     */
    private Container $container;

    /**
     * App constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get the container.
     *
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * Register services.
     *
     * @param array<string> $services
     * @return void
     */
    public function register(array $services)
    {
        $this->services = $services;

        foreach ($services as $service) {
            $class = app($service);

            if (is_subclass_of($class, ServiceProvider::class)) {
                $class->register($this->container);
            }
        }
    }

    /**
     * Get services.
     *
     * @return array
     */
    public function get_services(): array
    {
        return $this->services;
    }
}