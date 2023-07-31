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

use Drewlabs\Contracts\Auth\DoubleAuthUserInterface as Tokenable;
use Drewlabs\Auth\User\DI;
use Drewlabs\Auth\User\Auth;

trait TokenableAware
{
    /**
     * Get all of the user's registered OAuth clients.
     *
     * @return mixed
     */
    public function clients()
    {
        return DI::getInstance()->make(Tokenable::class)->fromAuthenticatable($this)->hasMany(Auth::clientModel(), 'user_id');
    }

    /**
     * Get all of the access tokens for the user.
     *
     * @return mixed
     */
    public function tokens()
    {
        return DI::getInstance()->make(Tokenable::class)->fromAuthenticatable($this)->hasMany(Auth::tokenModel(), 'user_id')->orderBy('created_at', 'desc');
    }
}
