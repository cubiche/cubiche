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

use Cubiche\Domain\Identity\UUID;

/**
 * UUID Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class UUIDType extends StringIdType
{
    /**
     * {@inheritdoc}
     *
     * @see StringIdType::nativeValueObjectClass()
     */
    protected function nativeValueObjectClass()
    {
        return UUID::class;
    }
}
