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

use Drewlabs\Collections\Collection;
use Drewlabs\Contracts\Auth\AuthorizableInterface;
use Drewlabs\Contracts\Auth\DoubleAuthUserInterface;
use Drewlabs\Contracts\Auth\IUserModel;
use Drewlabs\Contracts\Auth\IUserModel as UserModelContract;
use Drewlabs\Contracts\Auth\Notifiable;
use Drewlabs\Contracts\Auth\Verifiable;

use function Drewlabs\Support\Proxy\Collection;

trait AuthModelAware
{
    use AttributesAware;

    /**
     * @param UserModelContract|DoubleAuthUserInterface $model
     * @param bool                                      $all
     *
     * @return Authenticatable&AuthorizableInterface&Notifiable&Verifiable
     */
    public static function fromAuthModel($model, $all = true)
    {
        if (null === $model) {
            return new self();
        }
        // TODO : CONSTRUCT ATTRIBUTES
        $attributes = [];
        $attributes = array_merge($attributes, [
            'id' => $model->getIdentifier(),
            'username' => $model->getUserName(),
            'password' => $model->getPassword(),
            'is_active' => $model->getIsActive(),
            'remember_token' => $model->getRememberToken(),
            'user_details' => $model->getUserDetails(),
        ]);
        if ($model instanceof DoubleAuthUserInterface) {
            $attributes = array_merge($attributes, [
                'double_auth_active' => $model->getDoubleAuthActive(),
            ]);
        }
        // Enhance toAuthenticatable call in order to load notification channel binded object
        if ($model instanceof Notifiable) {
            $attributes = array_merge($attributes, [
                'channels' => $model->getChannels(),
            ]);
        }
        // Enhance toAuthenticatable call in order to load notification channel binded object
        if ($model instanceof Verifiable) {
            $attributes = array_merge($attributes, [
                'is_verified' => $model->isVerified(),
            ]);
        }

        $attributes = array_merge($attributes, [
            'authorizations' => $all ? (\is_array($model->authorizations) ? new Collection($model->authorizations) : $model->authorizations)->map(static function ($authorization) {
                return \is_string($authorization) ? $authorization : $authorization->label;
            })->all() : [],
            'roles' => $all ? (\is_array($model->roles) ? new Collection($model->roles) : $model->roles)->map(static function ($role) {
                return \is_string($role) ? $role : $role->label;
            })->all() : [],
        ]);

        return static::createFromAttributes($attributes);
    }
}
