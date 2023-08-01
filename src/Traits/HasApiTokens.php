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

use Drewlabs\Contracts\Auth\Authenticatable;
use Drewlabs\Contracts\Auth\AuthorizationsAware;
use Drewlabs\Contracts\OAuth\AccessTokenBridge;
use Drewlabs\Contracts\OAuth\PersonalAccessToken;
use Drewlabs\Contracts\OAuth\PersonalAccessTokenFactory;
use Drewlabs\Contracts\OAuth\Token;
use Drewlabs\Core\Helpers\Arr;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @mixin Authenticatable
 *
 * @property Token $accessToken
 */
trait HasApiTokens
{
    private $accessTokenFactory;

    /**
     * @var AccessTokenBridge
     */
    private $accessTokenBridge;

    /**
     * Get the current access token being used by the user.
     *
     * @return PersonalAccessToken|Token|null
     */
    public function token()
    {
        return $this->currentAccessToken();
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param string $scope
     *
     * @return bool
     */
    public function tokenCan($scope)
    {
        return $this->accessTokenCan($scope);
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param string $name
     *
     * @return PersonalAccessToken
     */
    public function createToken($name, array $scopes = ['*'])
    {
        $scopes = $scopes === ['*'] && $this instanceof AuthorizationsAware ? Arr::filter($this->getAuthorizations()) : $scopes;

        return $this->getAccessTokenFactory()->make($this->authIdentifier(), $name, $scopes);
    }

    /**
     * Set the current access token for the user.
     *
     * @param Token|PersonalAccessToken|mixed $accessToken
     *
     * @return self
     */
    public function withAccessToken($accessToken)
    {
        if (null === $accessToken) {
            return $this;
        }
        if ($accessToken instanceof Token) {
            $this->accessToken = $accessToken;

            return $this;
        }

        $this->accessToken = ($bridge = $this->getAccessTokenBridge()) ? $bridge->exchange($accessToken) : $accessToken;

        return $this;
    }

    public function currentAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set access token bridge for the current instance.
     *
     * @return static
     */
    public function setAccessTokenBridge(AccessTokenBridge $bridge)
    {
        $this->accessTokenBridge = $bridge;

        return $this;
    }

    /**
     * Returns access token bridge for the current instance.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return AccessTokenBridge
     */
    public function getAccessTokenBridge()
    {
        return $this->accessTokenBridge;
    }

    /**
     * Set access token factory for the current instance.
     *
     * @return static
     */
    public function setAccessTokenFactory(PersonalAccessTokenFactory $factory)
    {
        $this->accessTokenFactory = $factory;

        return $this;
    }

    /**
     * Returns access token factory for the current instance.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return PersonalAccessTokenFactory
     */
    public function getAccessTokenFactory()
    {
        return $this->accessTokenFactory;
    }

    private function accessTokenCan($scope)
    {
        return $this->accessToken ? $this->accessToken->can($scope) : false;
    }
}
