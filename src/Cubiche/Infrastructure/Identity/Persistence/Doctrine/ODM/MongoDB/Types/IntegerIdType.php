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

/**
 * Integer Id Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class IntegerIdType extends IdType
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Infrastructure\Model\Persistence\Doctrine\Common\Types\ValueObjectTypeInterface::targetClass()
     */
    public function targetClass()
    {
        return IntegerId::class;
    }
}
