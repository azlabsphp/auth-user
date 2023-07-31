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

use Drewlabs\Contracts\Auth\Authenticatable;
use Drewlabs\Contracts\Auth\AuthorizableInterface;
use Drewlabs\Contracts\OAuth\HasApiTokens;
use Drewlabs\Auth\User\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate as AbstractGate;
use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;
use PHPUnit\Framework\TestCase;

class AuthenticatableTest extends TestCase
{
    public $attributes = [];

    protected function setUp(): void
    {
        $this->attributes = require __DIR__.'/attributes.php';
        if (class_exists(Container::class)) {
            Container::getInstance()->singleton(AbstractGate::class, static function ($app) {
                return new Gate($app, static fn () => call_user_func($app['auth']->userResolver()));
            });
        }
    }

    public function test_Can_Create_From_Attributes()
    {
        /**
         * @var Authenticatable&AuthorizableInterface&AuthAuthenticatable
         */
        $auth = User::createFromAttributes($this->attributes);
        $this->assertInstanceOf(User::class, $auth);
        $this->assertInstanceOf(Authenticatable::class, $auth);
        $this->assertInstanceOf(AuthAuthenticatable::class, $auth);
        $this->assertInstanceOf(AuthorizableInterface::class, $auth);
        $this->assertIsArray($auth->getAuthorizations());
        $this->assertTrue(in_array('list-authorizations', $auth->getAuthorizations(), true));
        $this->assertIsArray($auth->getAuthorizationGroups());
        $this->assertSame('APPSYSADMIN', $auth->getAuthUserName());
        $this->assertSame([
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
        ], $auth->getUserDetails());
        $this->assertSame(1, intval($auth->authIdentifier()));
        $this->assertSame(1, intval($auth->getAuthIdentifier()));
    }

    public function test_Authorizable_Interface_Methods()
    {
        $auth = User::createFromAttributes($this->attributes);

        $this->assertIsArray($auth->getAuthorizations());
        $this->assertTrue(in_array('list-authorizations', $auth->getAuthorizations(), true));
        $this->assertIsArray($auth->getAuthorizationGroups());
        $this->assertTrue($auth->tokenCan('list-authorizations'));
        $this->assertFalse($auth->tokenCan('create-app'));
    }

    public function test_HasApiToken_Method()
    {
        /**
         * @var Authenticatable&AuthorizableInterface&HasApiTokens
         */
        $auth = User::createFromAttributes($this->attributes);
        $accessToken = AccessTokenStub::createFromAttributes($this->attributes['accessToken']);
        $auth->withAccessToken($accessToken);
        $this->assertSame($accessToken, $auth->currentAccessToken());
    }

    public function test_Authenticatable_Can_Always_Returns_False_For_AccessTokenStub()
    {
        /**
         * @var Authenticatable&AuthorizableInterface&HasApiTokens
         */
        $auth = User::createFromAttributes($this->attributes);
        $accessToken = AccessTokenStub::createFromAttributes($this->attributes['accessToken']);
        $auth->withAccessToken($accessToken);
        $this->assertFalse($auth->tokenCan('list-authorizations'));
    }

    public function test_Authenticatable_From_AuthModel()
    {
        $auth = User::fromAuthModel(UserStub::createFromAttributes($this->attributes));
        $this->assertInstanceOf(User::class, $auth);
        $this->assertInstanceOf(Authenticatable::class, $auth);
        $this->assertInstanceOf(AuthAuthenticatable::class, $auth);
        $this->assertInstanceOf(AuthorizableInterface::class, $auth);
        $this->assertIsArray($auth->getAuthorizations());
        $this->assertTrue(in_array('list-authorizations', $auth->getAuthorizations(), true));
        $this->assertIsArray($auth->getAuthorizationGroups());
        $this->assertSame('APPSYSADMIN', $auth->getAuthUserName());
        $this->assertSame([
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
        ], $auth->getUserDetails());
        $this->assertSame(1, intval($auth->authIdentifier()));
        $this->assertSame(1, intval($auth->getAuthIdentifier()));
    }
}
