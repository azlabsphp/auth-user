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

use Drewlabs\Contracts\Auth\Authenticatable as AbstractAuthenticatable;
use Drewlabs\Contracts\OAuth\HasApiTokens as OAuthHasApiTokens;
use Drewlabs\Core\Helpers\Arr;
use Drewlabs\Auth\User\Traits\AbilityAware;
use Drewlabs\Auth\User\Traits\AttributesAware;
use Drewlabs\Auth\User\Traits\Authenticatable as TraitsAuthenticatable;
use Drewlabs\Auth\User\Traits\AuthModelAware;
use Drewlabs\Auth\User\Traits\ForPassport;
use Drewlabs\Auth\User\Traits\HasApiTokens;
use Drewlabs\Auth\User\Traits\MustVerifyEmail;
use Drewlabs\Auth\User\Traits\TokenableAware;
use Drewlabs\Contracts\Auth\AuthorizationsAware;
use Drewlabs\Contracts\Auth\HasAbilities;
use Illuminate\Contracts\Auth\Authenticatable;
use LogicException;
use ReflectionException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

final class User implements Authenticatable, AbstractAuthenticatable, AuthorizationsAware, HasAbilities, OAuthHasApiTokens, \JsonSerializable, \IteratorAggregate, \ArrayAccess
{
    use AbilityAware;
    use AttributesAware;
    use AuthModelAware;
    use ForPassport;
    use HasApiTokens;
    use MustVerifyEmail;
    use TokenableAware;
    use TraitsAuthenticatable;

    /**
     * @var string[]
     */
    private $guards = [];

    /**
     * @var string[]
     */
    private $hidden = ['password'];

    /**
     * Creates user class instance
     * 
     * @param array $attributes 
     * @return void 
     * @throws LogicException 
     * @throws ReflectionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    public function __construct(array $attributes = [])
    {
        foreach (Arr::except($attributes, ['accessToken']) as $key => $value) {
            $this->attributes[$key] = $value;
        }
        $accessToken = AccessToken::createFromAttributes($attributes['accessToken'] ?? []);
        $this->withAccessToken($accessToken);
    }

    /**
     * Hidden property getter.
     *
     * @return array
     */
    public function getHidden()
    {
        return $this->hidden ?? [];
    }

    /**
     * Hidden property setter.
     *
     * @return void
     */
    public function setHidden(array $value)
    {
        $this->hidden = $value;
    }

    /**
     * {@inheritDoc}
     */
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
}
