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
use Drewlabs\Contracts\OAuth\Token;

interface TokensRepository
{
    /**
     * query tokens for a given user instance.
     *
     * @return \Traverable<Token>
     */
    public function findTokensByUser(Authenticatable $user): \Traversable;
}
