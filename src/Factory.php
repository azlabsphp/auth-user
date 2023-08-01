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

use Drewlabs\Auth\User\Contracts\AuthorizationGateInterface;
use Drewlabs\Auth\User\Contracts\ClientsRepository;
use Drewlabs\Auth\User\Contracts\PassportProvider;
use Drewlabs\Auth\User\Contracts\TokensRepository;
use Drewlabs\Contracts\Auth\AuthenticatableFactory;
use Drewlabs\Contracts\Auth\AuthorizationsAware;
use Drewlabs\Contracts\Auth\NotificationChannelsAware;
use Drewlabs\Contracts\Auth\UserInterface;
use Drewlabs\Contracts\Auth\UserMetdataAware;
use Drewlabs\Contracts\Auth\Verifiable;
use Drewlabs\Contracts\OAuth\AccessTokenBridge;
use Drewlabs\Contracts\OAuth\PersonalAccessTokenFactory;
use LogicException;
use ReflectionException;

class Factory implements AuthenticatableFactory
{
    /**
     * @var AuthorizationGateInterface
     */
    private $gate;

    /**
     * @var PersonalAccessTokenFactory
     */
    private $accessTokenFactory;

    /**
     * @var AccessTokenBridge
     */
    private $accessTokenBridge;

    /**
     * @var PassportProvider
     */
    private $passport;

    /**
     * @var ClientsRepository|null
     */
    private $clientsRepository;

    /**
     * @var TokensRepository|null
     */
    private $tokensRepository;

    /**
     * Creates authenticatable factory instance.
     */
    public function __construct(
        AuthorizationGateInterface $gate,
        PersonalAccessTokenFactory $accessTokenFactory,
        AccessTokenBridge $accessTokenBridge,
        PassportProvider $passport,
        ClientsRepository $clientsRepository = null,
        TokensRepository $tokensRepository = null
    ) {
        $this->gate = $gate;
        $this->accessTokenFactory = $accessTokenFactory;
        $this->accessTokenBridge = $accessTokenBridge;
        $this->passport = $passport;
        $this->clientsRepository = $clientsRepository;
        $this->tokensRepository = $tokensRepository;
    }

    /**
     * Creates authenticatable from attributes
     * 
     * @param array $attributes 
     * @return User 
     * @throws LogicException 
     * @throws ReflectionException 
     */
    public function __invoke(array $attributes): User
    {
        // Create object from attributes
        $instance = User::createFromAttributes($attributes);

        // Set dependency objects
        $instance->setAccessTokenBridge($this->accessTokenBridge);
        $instance->setGate($this->gate);
        $instance->setAccessTokenFactory($this->accessTokenFactory);
        $instance->setForPassport($this->passport);

        if ($this->clientsRepository) {
            $instance->setClientsRepository($this->clientsRepository);
        }

        if ($this->tokensRepository) {
            $instance->setTokensRepository($this->tokensRepository);
        }

        return $instance;
    }

    /**
     * Creates new authenticatable instance.
     *
     * Authenticatable instance is initialize with dependencies required
     * to work with access token, authorization gate and passport provider
     *
     * @throws \LogicException
     * @throws \ReflectionException
     */
    public function create(UserInterface $model): User
    {
        // Case the model is null return a default instance with no attribute
        if (null === $model) {
            return new User();
        }

        $attributes = array_merge([], [
            'id' => $model->getIdentifier(),
            'username' => $model->getUserName(),
            'password' => $model->getPassword(),
            'is_active' => $model->getIsActive(),
            'remember_token' => $model->getRememberToken(),
            'double_auth_active' => $model->getDoubleAuthActive(),
        ], $model instanceof UserMetdataAware ? [
            'emails' => $model->getEmails(),
            'email' => $model->getEmail(),
            'phone_number' => $model->getPhoneNumber(),
            'address' => $model->getAddress(),
            'profil_url' => $model->getProfileUrl(),
            'name' => $model->getName(),
        ] : []);

        // Enhance toAuthenticatable call in order to load notification channel binded object
        if ($model instanceof NotificationChannelsAware) {
            $attributes = array_merge($attributes, ['channels' => $model->getChannels()]);
        }
        // Enhance toAuthenticatable call in order to load notification channel binded object
        if ($model instanceof Verifiable) {
            $attributes = array_merge($attributes, ['is_verified' => $model->isVerified()]);
        }

        $instance = $this->__invoke($attributes);
        // Case the model is an instance of authorization aware, we add authorizations and groups to the
        // authenticatable instance
        if ($model instanceof AuthorizationsAware) {
            $instance->setAuthorizations($model->getAuthorizations());
            $instance->setAuthorizationGroups($model->getAuthorizationGroups());
        }

        return $instance;
    }
}
