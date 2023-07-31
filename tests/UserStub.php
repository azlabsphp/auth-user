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

use Drewlabs\Contracts\Auth\Authenticatable;
use Drewlabs\Contracts\Auth\DoubleAuthUserInterface;
use Drewlabs\Contracts\Auth\Notifiable;
use Drewlabs\Contracts\Auth\Verifiable;
use Drewlabs\Auth\User\Traits\AttributesAware;
use Drewlabs\Auth\User\User;

class UserStub implements DoubleAuthUserInterface, Notifiable, Verifiable
{
    use AttributesAware;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getChannels()
    {
        return $this->channels;
    }

    public function getDoubleAuthActive()
    {
        return $this->double_auth_active;
    }

    public function updateDoubleAuthActive($value = false)
    {
        $this->double_auth_active = $value;
    }

    public function getUserById($id)
    {
        return $this;
    }

    public function fetchUserByCredentials(array $credentials)
    {
        return $this;
    }

    public function updateUserRememberToken($id, $token)
    {
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getIsActive()
    {
        $this->is_active;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function getIdentifier()
    {
        return $this->id;
    }

    public function getLockEnabled()
    {
        return false;
    }

    public function getLockExpireAt()
    {
        return null;
    }

    public function getLoginAttempts()
    {
        return null;
    }

    public function getUserDetails()
    {
        return $this->user_details;
    }

    public function fromAuthenticatable(Authenticatable $authenticatable)
    {
    }

    public function toAuthenticatable(bool $loadRelations = true)
    {
        return User::createFromAttributes($this->attributes);
    }

    public function isVerified()
    {
        return $this->is_verified;
    }
}
