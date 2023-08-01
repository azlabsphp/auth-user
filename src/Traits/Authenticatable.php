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

trait Authenticatable
{
    use AttributesAware;
    use Authorizable;
    use Tokenable;

    /**
     * @var string[]
     */
    private $hidden = ['password'];

    public function getAuthIdentifierName()
    {
        return $this->authIdentifierName();
    }

    public function getAuthIdentifier()
    {
        return $this->authIdentifier();
    }

    public function getAuthPassword()
    {
        return $this->authPassword();
    }

    public function getRememberToken()
    {
        return $this->rememberToken();
    }

    public function setRememberToken($value)
    {
        return $this->rememberToken($value);
    }

    public function getRememberTokenName()
    {
        return $this->rememberTokenName();
    }

    public function authIdentifier()
    {
        return (string) $this->getAttribute($this->authIdentifierName());
    }

    public function authPassword()
    {
        return $this->getAttribute($this->authPasswordName());
    }

    public function rememberToken($token = null)
    {
        if (null === $token) {
            return $this->getAttribute($this->rememberTokenName());
        }
        $this->setAttribute($this->rememberTokenName(), $token);
    }

    public function getHidden()
    {
        return $this->hidden ?? [];
    }

    public function setHidden(array $value)
    {
        $this->hidden = $value;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        $hidden = $this->getHidden();
        $attributes = [];
        foreach ($this as $name => $value) {
            if (!\in_array($name, $hidden, true)) {
                $attributes[$name] = $value;
            }
        }

        return $attributes;
    }

    public function getAuthUserName()
    {
        return $this->getAttribute('username');
    }

    public function authPasswordName()
    {
        return 'password';
    }

    public function rememberTokenName()
    {
        return 'remember_token';
    }

    public function authIdentifierName()
    {
        return 'id';
    }
}
