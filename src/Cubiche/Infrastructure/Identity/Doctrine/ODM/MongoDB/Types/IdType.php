<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB\Types;

use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types\NativeValueObjectType;

/**
 * Abstract Id Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class IdType extends NativeValueObjectType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        $class = $this->targetClass();
        if (!$value instanceof $class) {
            return $value;
        }

        return parent::convertToDatabaseValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function closureToPHP()
    {
        $class = $this->targetClass();

        return '$return = $value !== null && $value instanceof '.$class.' ? '.$class.'::fromNative($value) : $value;';
    }
}
