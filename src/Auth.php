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

class Auth
{

    /**
     * @var string
     */
    private static $users;

    /**
     * @var string
     */
    private static $clients;

    /**
     * @var string
     */
    private static $tokens;

    /**
     * @var bool
     */
    private static $useAuthorizations = true;

    /**
     * @return string
     */
    public static function userModel(string $value = null)
    {
        if (null !== $value) {
            self::$users = $value;
        }

        return self::$users;
    }

    /**
     * Defines the model to use as client model.
     *
     * @return void
     */
    public static function useClientModel(?string $value)
    {
        self::$clients = $value;
    }

    /**
     * Defines the model to use as token model.
     *
     * @return void
     */
    public static function useTokenModel(?string $value)
    {
        self::$clients = $value;
    }

    /**
     * Returns the model used as client model.
     *
     * @return string
     */
    public static function clientModel()
    {
        return self::$clients;
    }

    /**
     * Returns the model uses as token model.
     *
     * @return string
     */
    public static function tokenModel()
    {
        return self::$tokens;
    }

    public static function dontUseAuthorizationAsTokenAbilities()
    {
        static::$useAuthorizations = false;
    }

    public static function usingAuthorizationsAsTokenAbility()
    {
        return static::$useAuthorizations;
    }
}
