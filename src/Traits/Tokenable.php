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

use Drewlabs\Auth\User\Contracts\ClientsRepository;
use Drewlabs\Auth\User\Contracts\TokensRepository;
use Drewlabs\Contracts\Auth\Authenticatable;
use Drewlabs\Contracts\OAuth\Token;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @mixin Authenticatable
 */
trait Tokenable
{
    /**
     * @var ClientsRepository
     */
    private $clientsRepository;

    /**
     * @var TokensRepository
     */
    private $tokensRepository;

    /**
     * Get all of the user's registered oauth clients.
     *
     * @return \Traversable
     */
    public function clients()
    {
        return $this->getClientsRepository()->findClientsByUser($this);
    }

    /**
     * Get all of the access tokens for the user.
     *
     * @return \Traversable<Token>
     */
    public function tokens()
    {
        return $this->getTokensRepository()->findTokensByUser($this);
    }

    /**
     * Set clients repository instance.
     *
     * @return static
     */
    public function setClientsRepository(ClientsRepository $repository)
    {
        $this->clientsRepository = $repository;

        return $this;
    }

    /**
     * Returns client repository instance.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return ClientsRepository
     */
    public function getClientsRepository()
    {
        return $this->clientsRepository;
    }

    /**
     * Set tokens repository instance.
     *
     * @return static
     */
    public function setTokensRepository(TokensRepository $repository)
    {
        $this->tokensRepository = $repository;

        return $this;
    }

    /**
     * Returns tokens repository instance.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return TokensRepository
     */
    public function getTokensRepository()
    {
        return $this->tokensRepository;
    }
}
