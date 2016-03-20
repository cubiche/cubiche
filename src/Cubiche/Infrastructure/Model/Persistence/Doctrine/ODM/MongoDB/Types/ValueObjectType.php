<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Model\Persistence\Doctrine\ODM\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type as BaseType;
use Cubiche\Infrastructure\Model\Persistence\Doctrine\Common\Types\ValueObjectTypeInterface;
use Cubiche\Domain\Model\ValueObjectInterface;

/**
 * Abstract Value Object Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ValueObjectType extends BaseType implements ValueObjectTypeInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Infrastructure\Model\Persistence\Doctrine\Common\Types\ValueObjectTypeInterface::toDatabaseValue()
     */
    final public function toDatabaseValue(ValueObjectInterface $value)
    {
        return $this->convertToDatabaseValue($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see ValueObjectTypeInterface::fromDatabaseValue()
     */
    final public function fromDatabaseValue($value)
    {
        return $this->convertToPHPValue($value);
    }
}
