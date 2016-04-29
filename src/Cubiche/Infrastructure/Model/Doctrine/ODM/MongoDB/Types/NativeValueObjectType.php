<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types;

/**
 * Abstract Native Value Object Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class NativeValueObjectType extends ValueObjectType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        return $value !== null ? $value->toNative() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        $class = $this->targetClass();

        return $value !== null ? $class::fromNative($value) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function closureToMongo()
    {
        return '$return = $value !== null ? $value->toNative() : null;';
    }

    /**
     * {@inheritdoc}
     */
    public function closureToPHP()
    {
        $class = $this->targetClass();

        return 'return $value !== null ? '.$class.'::fromNative($value) : null;';
    }

    /**
     * @return string
     */
    abstract public function targetClass();
}
