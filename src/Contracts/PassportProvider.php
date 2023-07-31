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

namespace Drewlabs\Auth\User\Contracts;

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
     */
    public function validatePasswordCredentials(Authenticatable $user, string $password): bool;
}
