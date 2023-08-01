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

namespace Drewlabs\Auth\User\Traits;

use Drewlabs\Auth\User\Contracts\AuthorizationGateInterface;
use Drewlabs\Contracts\OAuth\HasApiTokens as HasApiTokensInterface;
use Drewlabs\Core\Helpers\Arr;
use Drewlabs\Core\Helpers\Reflector;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @internal
 *
 * @mixin AttributesAware
 *
 * @property string[] $authorizations
 * @property string[] $authorization_groups
 */
trait Authorizable
{
    /**
     * @var AuthorizationGateInterface
     */
    private $gate;

    public function getAuthorizations(): array
    {
        return $this->authorizations ?? [];
    }

    public function getAuthorizationGroups(): array
    {
        return $this->authorization_groups ?? [];
    }

    public function setAuthorizations(array $value = [])
    {
        $this->authorizations = $value;

        return $this;
    }

    public function setAuthorizationGroups(array $value = [])
    {
        $this->authorization_groups = $value;

        return $this;
    }

    /**
     * Checks if the current is aware of a given acl authorization.
     *
     * @return void
     */
    public function hasAbility(string $ability)
    {
        return \in_array($ability, $this->getAuthorizations(), true);
    }

    /**
     * Checks if the current is aware of all given acl authorizations.
     *
     * @return void
     */
    public function hasAbilities(array $abilities = [])
    {
        return Arr::containsAll($this->getAuthorizations() ?? [], $abilities);
    }

    /**
     * Determine if the entity has a given ability.
     *
     * @param string      $ability
     * @param array|mixed $arguments
     *
     * @return bool
     */
    public function can($ability, $arguments = [])
    {
        return $this->getGate()->forUser($this)->check($ability, $arguments);
    }

    /**
     * Determine if the entity does not have a given ability.
     *
     * @param string      $ability
     * @param array|mixed $arguments
     *
     * @return bool
     */
    public function cant($ability, $arguments = [])
    {
        return !$this->can($ability, $arguments);
    }

    /**
     * Determine if the entity does not have a given ability.
     *
     * @param string      $ability
     * @param array|mixed $arguments
     *
     * @return bool
     */
    public function cannot($ability, $arguments = [])
    {
        return $this->cant($ability, $arguments);
    }

    public function supportsToken()
    {
        return (($this instanceof HasApiTokensInterface) && \is_object($this->currentAccessToken()))
            || (\in_array(HasApiTokens::class, Reflector::usesRecursive(static::class) ?? [], true) && \is_object($this->currentAccessToken()));
    }

    public function setGate(AuthorizationGateInterface $gate)
    {
        $this->gate = $gate;

        return $this;
    }

    /**
     * Returns the gate instance property value.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return AuthorizationGateInterface
     */
    public function getGate()
    {
        return $this->gate;
    }
}
