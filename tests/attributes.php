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

return [
    'id' => 1,
    'username' => 'APPSYSADMIN',
    'is_active' => true,
    'remember_token' => null,
    'double_auth_active' => false,
    'authorization_groups' => ['SYSADMIN'],
    'authorizations' => [
        'all',
        'list-authorizations',
        'create-authorizations',
        'update-authorizations',
        'delete-authorizations',
        'list-roles',
        'create-roles',
        'update-roles',
        'delete-roles',
        'list-users',
        'create-users',
        'update-users',
        'delete-users',
    ],
    'user_details' => [
        'id' => 1,
        'firstname' => 'ADMIN',
        'lastname' => 'MASTER',
        'address' => null,
        'phone_number' => null,
        'postal_code' => null,
        'birthdate' => null,
        'sex' => null,
        'profile_url' => null,
        'deleted_at' => null,
        'created_at' => '2022-01-06T11:31:58.000000Z',
        'updated_at' => '2022-01-06T11:31:58.000000Z',
        'emails' => ['contact@azlabs.tg'],
    ],
    'channels' => [],
    'is_verified' => true,
    'organizational' => null,
    'accessToken' => [
        'authToken' => null,
        'provider' => 'local',
        'id' => 1,
        'idToken' => '81d7e40521794949d11f0ad2bcf3e12e55aa40e834a63950ec57e9b39cd5cdaa0cc26fe131c4944e',
        'authorizationCode' => null,
        'response' => null,
        'scopes' => [
            'list-authorizations',
            'create-authorizations',
            'update-authorizations',
            'delete-authorizations',
            'list-roles',
            'create-roles',
            'update-roles',
            'delete-roles',
            'list-users',
            'create-users',
            'update-users',
            'delete-users',
        ],
        'expires_at' => '2022-01-29T14:20:27.000000Z',
    ],
];
