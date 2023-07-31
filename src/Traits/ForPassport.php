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

use Drewlabs\Auth\User\Contracts\PassportProvider;
use Drewlabs\Auth\User\DI;

trait ForPassport
{
    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     */
    public function findForPassport($username)
    {
        return DI::getInstance()->make(PassportProvider::class)->findByLogin($username);
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param string $password
     *
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return DI::getInstance()->make(PassportProvider::class)->validatePasswordCredentials($password);
    }
}
