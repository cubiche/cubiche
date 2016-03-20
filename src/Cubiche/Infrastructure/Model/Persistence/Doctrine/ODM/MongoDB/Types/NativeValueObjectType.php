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

/**
 * Abstract Native Value Object Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class NativeValueObjectType extends ValueObjectType
{
    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::convertToDatabaseValue()
     */
    public function convertToDatabaseValue($value)
    {
        return $value !== null ? $value->toNative() : null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::convertToPHPValue()
     */
    public function convertToPHPValue($value)
    {
        $class = $this->targetClass();

        return $value !== null ? $class::fromNative($value) : null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::closureToMongo()
     */
    public function closureToMongo()
    {
        return '$return = $value !== null ? $value->toNative() : null;';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::closureToPHP()
     */
    public function closureToPHP()
    {
        $class = $this->targetClass();

        return 'return $value !== null ? '.$class.'::fromNative($value) : null;';
    }
}
