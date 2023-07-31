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
use Drewlabs\Core\Helpers\Arr;

class AccessToken implements HasAbilities, Token
{
    use AttributesAware;

    /**
     * Creates access token class.
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function revoke()
    {
        // TODO: Throw a BadMethodCallException as this access token does not revoke
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
        Arr::set($this->attributes, 'authToken', $value);
    }
}
