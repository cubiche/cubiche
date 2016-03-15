<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Identity\Persistence\Doctrine\ODM\MongoDB\Types;

use Cubiche\Domain\Identity\IntegerId;
use Cubiche\Infrastructure\Model\Persistence\Doctrine\ODM\MongoDB\Types\NativeValueObjectType;

/**
 * Integer Id Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class IntegerIdType extends NativeValueObjectType
{
    /**
     * {@inheritdoc}
     *
     * @see NativeValueObjectType::nativeValueObjectClass()
     */
    protected function nativeValueObjectClass()
    {
        return IntegerId::class;
    }
}
