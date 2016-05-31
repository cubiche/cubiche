<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types;

use Cubiche\Domain\System\Enum;
use Doctrine\ODM\MongoDB\Types\StringType;

/**
 * EnumType Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class EnumType extends StringType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        /* @var Enum $value */
        return $value !== null ? $value->toNative() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        $enumClass = $this->enumClass();

        return $value !== null ? $enumClass::fromNative($value) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function closureToMongo()
    {
        return '$return = $value->toNative();';
    }

    /**
     * {@inheritdoc}
     */
    public function closureToPHP()
    {
        $enumClass = $this->enumClass();

        return '$return = '.$enumClass.'::fromNative($value);';
    }

    /**
     * @return string
     */
    abstract protected function enumClass();
}
