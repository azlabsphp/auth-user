<?php

declare(strict_types=1);

/*
 * This file is part of the drewlabs namespace.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drewlabs\Auth\User;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class DI
{
    private static $instance;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Private DI constructor.
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Provides an interface to create a singleton.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Creates an instance from an abstraction declaration or.
     *
     * @param mixed $abstract
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return mixed|null
     */
    public function make($abstract, array $parameters = [])
    {
        try {
            return !\is_string($abstract) && \is_callable($abstract) ? \call_user_func_array($abstract, $parameters) : $this->getContainer()->get($abstract);
        } catch (\Throwable $th) {
            // We return null if we were not able to locate factory from the container
            return null;
        }
    }

    /**
     * Set the container instance to be used by the DI class.
     *
     * @return void
     */
    public static function setContainer(ContainerInterface $container)
    {
        static::getInstance()->container = $container;
    }

    /**
     * returns the container instance attached to the DI class.
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        if (null === $this->container) {
            throw new RuntimeException('No container instance found');
        }
        return $this->container;
    }
}
