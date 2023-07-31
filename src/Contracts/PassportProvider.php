<?php

namespace Drewlabs\Auth\User;

use Drewlabs\Contracts\Auth\Authenticatable;

interface PassportProvider
{
    /**
     * Try resolving user by login name.
     *
     * @return Authenticatable
     */
    public function findByLogin(string $username);

    /**
     * Validate user login secret key.
     *
     * @return bool
     */
    public function validatePasswordCredentials(Authenticatable $user, string $password): bool;
}
