<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Repository\Tests\Units\Doctrine\ODM\MongoDB\Types;

use Cubiche\Domain\Repository\Tests\Fixtures\Role;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types\EnumType;

/**
 * RoleType Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RoleType extends EnumType
{
    /**
     * {@inheritdoc}
     */
    protected function enumClass()
    {
        return Role::class;
    }
}
