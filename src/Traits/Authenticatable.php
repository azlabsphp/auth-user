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

use Drewlabs\Contracts\Auth\AuthorizationsAware;
use Drewlabs\Contracts\Auth\NotificationChannelsAware;
use Drewlabs\Contracts\Auth\UserInterface;
use Drewlabs\Contracts\Auth\UserMetdataAware;
use Drewlabs\Contracts\Auth\Verifiable;

trait Authenticatable
{
    use AttributesAware;
    use Authorizable;

    /**
     * @var string[]
     */
    private $hidden = ['password'];

    /**
     * @param UserInterface $model
     *
     * @return static
     */
    public static function fromAuthModel($model)
    {
        // Case the model is null return a default instance with no attribute
        if (null === $model) {
            return new self();
        }

        $attributes = array_merge([], [
            'id' => $model->getIdentifier(),
            'username' => $model->getUserName(),
            'password' => $model->getPassword(),
            'is_active' => $model->getIsActive(),
            'remember_token' => $model->getRememberToken(),
            'double_auth_active' => $model->getDoubleAuthActive(),
        ], $model instanceof UserMetdataAware ? [
            'emails' => $model->getEmails(),
            'email' => $model->getEmail(),
            'phone_number' => $model->getPhoneNumber(),
            'address' => $model->getAddress(),
            'profil_url' => $model->getProfileUrl(),
            'name' => $model->getName(),
        ] : []);

        // Enhance toAuthenticatable call in order to load notification channel binded object
        if ($model instanceof NotificationChannelsAware) {
            $attributes = array_merge($attributes, ['channels' => $model->getChannels()]);
        }
        // Enhance toAuthenticatable call in order to load notification channel binded object
        if ($model instanceof Verifiable) {
            $attributes = array_merge($attributes, ['is_verified' => $model->isVerified()]);
        }
        // Create object from attributes
        $object = static::createFromAttributes($attributes);

        // Case the model is an instance of authorization aware, we add authorizations and groups to the
        // authenticatable instance
        if ($model instanceof AuthorizationsAware) {
            $object->setAuthorizations($model->getAuthorizations());
            $object->setAuthorizationGroups($model->getAuthorizationGroups());
        }

        return $object;
    }

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
