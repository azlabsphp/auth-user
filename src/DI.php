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

use Drewlabs\Auth\User\Exceptions\InjectionException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DI
{
    /**
     * @var static
     */
    private static $instance;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array<string,callable>
     */
    private $resolvers = [];

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
     * Configure providers that might be used when abstracts are requested.
     *
     * @return void
     */
    public static function configure(array $providers)
    {
        foreach ($providers as $key => $provider) {
            if (!\is_string($provider) && \is_callable($provider)) {
                static::getInstance()->provide($key, $provider);
            }
        }
    }

    /**
     * Add a creator function for a given abstraction.
     *
     * @return void
     */
    public function provide(string $abstract, callable $factory)
    {
        $this->resolvers[$abstract] = static function (self $injector) use ($factory) {
            return \call_user_func_array($factory, [$injector]);
        };
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
            // First try to resolve dependency from DI resolvers
            if (\array_key_exists($abstract, $this->resolvers)) {
                return \call_user_func_array($this->resolvers[$abstract], [$this, ...$parameters]);
            }

            // Case the container is not set, we simply
            if (null === ($container = $this->getContainer())) {
                print_r(['abstract' => $abstract]);
                throw new InjectionException('No container instance provided for the DI, use DI::setContainer() at the root of your application to set the container used by the library');
            }

            return $container->get($abstract);
        } catch (\Throwable $th) {
            // We return null if we were not able to locate factory from the container
            throw new InjectionException($th->getMessage(), $th->getCode(), $th);
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
        return $this->container;
    }
}
