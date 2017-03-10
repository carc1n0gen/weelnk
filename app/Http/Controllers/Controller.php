<?php

namespace App\Http\Controllers;

use Interop\Container\ContainerInterface;

/**
 * A base controller with properties to be inherited by all controllers.
 */
class Controller
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
