<?php

/**
 * This file is part of the Cubiche/Cqrs component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Cqrs\Tests\Fixtures;

use Cubiche\Infrastructure\Cqrs\Tests\Fixtures\Query\FindOneUserByIdQuery;

/**
 * UserQueryHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class UserQueryHandler
{
    /**
     * @param FindOneUserByIdQuery $query
     */
    public function findOneUserById(FindOneUserByIdQuery $query)
    {
    }
}
