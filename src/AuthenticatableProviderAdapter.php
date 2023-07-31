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

use Drewlabs\Auth\User\Contracts\PassportProvider;
use Drewlabs\Contracts\Auth\Authenticatable;
use Drewlabs\Contracts\Auth\AuthenticatableProvider;

class AuthenticatableProviderAdapter implements PassportProvider
{
    /**
     * @var AuthenticatableProvider
     */
    private $provider;

    /**
     * Creates class instance.
     */
    public function __construct(AuthenticatableProvider $provider)
    {
        $this->provider = $provider;
    }

    public function findByLogin(string $username)
    {
        return $this->provider->findByLogin($username);
    }

    public function validatePasswordCredentials(Authenticatable $user, string $password): bool
    {
        return (bool) $this->provider->validateAuthSecret($user, $password);
    }
}
