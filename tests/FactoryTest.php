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

use Drewlabs\Auth\User\Contracts\AuthorizationGateInterface;
use Drewlabs\Auth\User\Contracts\ClientsRepository;
use Drewlabs\Auth\User\Contracts\PassportProvider;
use Drewlabs\Auth\User\Contracts\TokensRepository;
use Drewlabs\Auth\User\Factory;
use Drewlabs\Auth\User\User;
use Drewlabs\Contracts\Auth\Authenticatable;
use Drewlabs\Contracts\Auth\AuthorizationsAware;
use Drewlabs\Contracts\OAuth\AccessTokenBridge;
use Drewlabs\Contracts\OAuth\PersonalAccessTokenFactory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function test_factory_create_set_authenticatable_attributes()
    {
        // Initialize
        $gate = $this->createMock(AuthorizationGateInterface::class);
        $accessTokenFactory = $this->createMock(PersonalAccessTokenFactory::class);
        $accessTokenBridge = $this->createMock(AccessTokenBridge::class);
        $passport = $this->createMock(PassportProvider::class);
        $factory = new Factory($gate, $accessTokenFactory, $accessTokenBridge, $passport);

        // Act
        $auth = $factory->create(UserStub::createFromAttributes(require __DIR__.'/attributes.php'));

        // Assert
        $this->assertInstanceOf(User::class, $auth);
        $this->assertInstanceOf(Authenticatable::class, $auth);
        $this->assertInstanceOf(AuthorizationsAware::class, $auth);
        $this->assertIsArray($auth->getAuthorizations());
        $this->assertTrue(in_array('list-authorizations', $auth->getAuthorizations(), true));
        $this->assertIsArray($auth->getAuthorizationGroups());
        $this->assertSame('APPSYSADMIN', $auth->getAuthUserName());
        $this->assertSame(1, (int) $auth->authIdentifier());
        $this->assertSame(1, (int) $auth->getAuthIdentifier());
    }

    public function test_factory_create_set_dependency_properties()
    {
        // Initialize
        $gate = $this->createMock(AuthorizationGateInterface::class);
        $accessTokenFactory = $this->createMock(PersonalAccessTokenFactory::class);
        $accessTokenBridge = $this->createMock(AccessTokenBridge::class);
        $passport = $this->createMock(PassportProvider::class);
        $clientsRepository = $this->createMock(ClientsRepository::class);
        $tokensRepository = $this->createMock(TokensRepository::class);
        $factory = new Factory($gate, $accessTokenFactory, $accessTokenBridge, $passport, $clientsRepository, $tokensRepository);

        // Act
        $auth = $factory->create(UserStub::createFromAttributes(require __DIR__.'/attributes.php'));

        // Assert
        $this->assertInstanceOf(AuthorizationGateInterface::class, $auth->getGate());
        $this->assertInstanceOf(PersonalAccessTokenFactory::class, $auth->getAccessTokenFactory());
        $this->assertInstanceOf(AccessTokenBridge::class, $auth->getAccessTokenBridge());
        $this->assertInstanceOf(PassportProvider::class, $auth->getForPassport());
        $this->assertInstanceOf(ClientsRepository::class, $auth->getClientsRepository());
        $this->assertInstanceOf(TokensRepository::class, $auth->getTokensRepository());

    }
}
