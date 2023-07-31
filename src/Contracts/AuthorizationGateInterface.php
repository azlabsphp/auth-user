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

interface AuthorizationGateInterface
{
    /**
     * Determine if all of the given abilities should be granted for the current user.
     *
     * @param iterable|string $abilities
     * @param array|mixed     $arguments
     *
     * @return bool
     */
    public function check($abilities, $arguments = []);

    /**
     * Get authorization guard instance for the given user.
     *
     * @return static
     */
    public function forUser(Authenticatable $user);
}
