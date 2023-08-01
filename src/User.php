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

use Drewlabs\Auth\User\Traits\Authenticatable as AuthenticatableMixin;
use Drewlabs\Auth\User\Traits\ForPassport;
use Drewlabs\Auth\User\Traits\HasApiTokens;
use Drewlabs\Contracts\Auth\Authenticatable as AbstractAuthenticatable;
use Drewlabs\Contracts\Auth\AuthorizationsAware;
use Drewlabs\Contracts\Auth\HasAbilities;
use Drewlabs\Contracts\OAuth\HasApiTokens as OAuthHasApiTokens;
use Drewlabs\Core\Helpers\Arr;
use Illuminate\Contracts\Auth\Authenticatable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class User implements Authenticatable, AbstractAuthenticatable, AuthorizationsAware, HasAbilities, OAuthHasApiTokens, \JsonSerializable, \IteratorAggregate, \ArrayAccess
{
    use AuthenticatableMixin;
    use ForPassport;
    use HasApiTokens;

    /**
     * Creates user class instance.
     *
     * @throws \LogicException
     * @throws \ReflectionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        foreach (Arr::except($attributes, ['accessToken']) as $key => $value) {
            $this->attributes[$key] = $value;
        }

        $accessToken = \is_array($token = $attributes['accessToken'] ?? null) ? new AccessToken($token) : (\is_object($token) ? $token : new AccessToken());
        $this->withAccessToken($accessToken);
    }
}
