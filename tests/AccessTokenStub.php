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

use Drewlabs\Auth\User\Traits\AttributesAware;
use Drewlabs\Contracts\OAuth\Token;
use Drewlabs\Core\Helpers\Arr;
use Drewlabs\Core\Helpers\ImmutableDateTime;

class AccessTokenStub implements Token
{
    use AttributesAware;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function revoke()
    {
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
        return false;
    }

    public function cant($ability)
    {
        return !$this->can($ability);
    }

    public function expires()
    {
        $expires_at = $this->expiresAt();
        if (null === $expires_at) {
            return true;
        }

        return !ImmutableDateTime::isfuture(new DateTimeImmutable($expires_at));
    }

    public function expiresAt()
    {
        return $this->expires_at;
    }

    public function setAccessToken($value)
    {
        Arr::set($this->attributes, 'authToken', $value);
    }
}
