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

use Drewlabs\Auth\User\Traits\AttributesAware;
use Drewlabs\Contracts\OAuth\HasAbilities;
use Drewlabs\Contracts\OAuth\Token;

/**
 * Trasient access token 
 * @package Drewlabs\Auth\User
 */
class AccessToken implements HasAbilities, Token
{
    use AttributesAware;

    /**
     * @var array<callable>
     */
    private $revokeTokenListeners = [];

    /**
     * Creates access token class.
     */
    public function __construct(array $attributes = [], callable $revokeHandler = null)
    {
        $this->attributes = $attributes;
        if ($revokeHandler) {
            $this->addRevokeTokenLister($revokeHandler);
        }
    }

    public function revoke()
    {
        foreach ($this->revokeTokenListeners ?? [] as $listener) {
            call_user_func_array($listener, [$this]);
        }
    }

    public function transient()
    {
        return false;
    }

    public function abilities()
    {
        return $this->scopes ?? [];
    }

    public function can($ability)
    {
        $abilities = $this->abilities();

        return \in_array('*', $abilities, true) || \array_key_exists($ability, array_flip($abilities));
    }

    public function cant($ability)
    {
        return !$this->can($ability);
    }

    public function setAccessToken($value)
    {
        $this->setAttribute('authToken', $value);
    }

    /**
     * Add a revoke token listener for revoke token event
     * 
     * @param callable $callback
     * 
     * @return void 
     */
    public function addRevokeTokenLister(callable $callback)
    {
        $this->revokeTokenListeners[] = $callback;
    }

    /**
     * Returns the access token string value
     * 
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->getAttribute('authToken', null);
    }
}
