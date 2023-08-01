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
use Drewlabs\Contracts\Auth\Authenticatable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @mixin Authenticatable
 */
trait ForPassport
{
    /**
     * @var PassportProvider
     */
    private $passportProvider;

    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     */
    public function findForPassport($username)
    {
        return $this->getForPassport()->findByLogin($username);
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
        return $this->getForPassport()->validatePasswordCredentials($this, $password);
    }

    /**
     * Set passport provider instance.
     *
     * @return static
     */
    public function setForPassport(PassportProvider $passport)
    {
        $this->passportProvider = $passport;

        return $this;
    }

    /**
     * Get passport provider instance.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return PassportProvider
     */
    public function getForPassport()
    {
        return $this->passportProvider;
    }
}
