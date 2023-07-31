<?php

namespace Drewlabs\Auth\User;

use Drewlabs\Contracts\Auth\Authenticatable;
use Drewlabs\Contracts\Auth\AuthenticatableProvider;

class AuthenticatableProviderAdapter implements PassportProvider
{
    /**
     * @var AuthenticatableProvider
     */
    private $provider;

    /**
     * Creates class instance
     * @param AuthenticatableProvider $provider 
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
        return boolval($this->provider->validateAuthSecret($user, $password));
    }
}
