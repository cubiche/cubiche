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

use Cubiche\Core\Validator\Mapping\ClassMetadata;
use Cubiche\Infrastructure\Cqrs\Tests\Fixtures\Command\CreateUserCommand;
use Cubiche\Infrastructure\Cqrs\Tests\Fixtures\Query\FindOneUserByIdQuery;

/**
 * UserValidatorHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class UserValidatorHandler
{
    /**
     * @param CreateUserCommand $command
     * @param ClassMetadata     $classMetadata
     */
    public function createUserValidator(CreateUserCommand $command, ClassMetadata $classMetadata)
    {
    }

    /**
     * @param FindOneUserByIdQuery $query
     * @param ClassMetadata        $classMetadata
     */
    public function findOneUserByIdValidator(FindOneUserByIdQuery $query, ClassMetadata $classMetadata)
    {
    }
}
