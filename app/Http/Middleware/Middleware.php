<?php

namespace App\Http\Middleware;

use Interop\Container\ContainerInterface;

/**
 * A base middleware with properties to be inherited by all middleware.
 */
class Middleware
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Allow direct access to the $container
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->container->get($key);
    }
}
