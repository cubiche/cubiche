<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Repository\Doctrine\Tests\Units\ODM\MongoDB\Types;

use Cubiche\Domain\Repository\Tests\Fixtures\Phonenumber;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types\NativeValueObjectType;

/**
 * Phonenumber Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PhonenumberType extends NativeValueObjectType
{
    /**
     * @return string
     */
    public function targetClass()
    {
        return Phonenumber::class;
    }
}
