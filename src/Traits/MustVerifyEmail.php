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

use Drewlabs\Auth\User\Auth;
use Drewlabs\Contracts\Auth\IUserModel as User;

trait MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        $user = $this->createUserModel()->fromAuthenticatable($this);

        return null !== $user ? (null !== $user->email_verified_at) : false;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        $user = $this->createUserModel()->fromAuthenticatable($this);

        return $user ? $user->forceFill([
            'email_verified_at' => $user->freshTimestamp(),
            'is_verified' => true,
        ])->save() : $user;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        if ($user = $this->createUserModel()->fromAuthenticatable($this)) {
            $user->notify(new \Illuminate\Auth\Notifications\VerifyEmail());
        }
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return (null !== $user = $this->createUserModel()->fromAuthenticatable($this)) ? $user->email : null;
    }

    /**
     * Load user model form the auth configuration defined in the config folder at the root of the application.
     *
     * @return User|mixed
     */
    protected function createUserModel()
    {
        if (null === ($userModel = Auth::userModel())) {
            throw new \RuntimeException('User model is not registered. Make sure you call AuthLaravelPassport::userModel(<Path/To/UserModel/Class>), in your application service provider');
        }

        return new $userModel();
    }
}
