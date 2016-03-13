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

/**
 * Abstract Native Value Object Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class NativeValueObjectType extends BaseType
{
    /**
     * @return string
     */
    abstract protected function nativeValueObjectClass();

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::convertToDatabaseValue()
     */
    public function convertToDatabaseValue($value)
    {
        if ($value === null) {
            return;
        }
        $class = $this->nativeValueObjectClass();
        if (!$value instanceof $class) {
            return $value;
        }

        return $value->toNative();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::convertToPHPValue()
     */
    public function convertToPHPValue($value)
    {
        $class = $this->nativeValueObjectClass();

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
        $class = $this->nativeValueObjectClass();

        return 'return $value !== null && $value instanceof '.$class.' ? '.$class.'::fromNative($value) : $value;';
    }
}
