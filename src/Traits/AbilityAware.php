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

use Drewlabs\Core\Helpers\Arr;

trait AbilityAware
{
    /**
     * Checks if the current is aware of a given acl authorization.
     *
     * @return void
     */
    public function hasAbility(string $ability)
    {
        return \in_array($ability, $this->getAuthorizations(), true);
    }

    /**
     * Checks if the current is aware of all given acl authorizations.
     *
     * @return void
     */
    public function hasAllAbilities(array $abilities = [])
    {
        return Arr::containsAll($this->getAuthorizations() ?? [], $abilities);
    }
}
