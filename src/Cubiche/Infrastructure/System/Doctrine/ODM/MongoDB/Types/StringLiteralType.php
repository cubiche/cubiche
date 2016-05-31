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

use Cubiche\Domain\System\StringLiteral;
use Doctrine\ODM\MongoDB\Types\StringType;

/**
 * StringLiteralType Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class StringLiteralType extends StringType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        /* @var StringLiteral $value */
        return $value !== null ? $value->toNative() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        return $value !== null ? StringLiteral::fromNative($value) : null;
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
        return '$return = \Cubiche\Domain\System\StringLiteral::fromNative($value);';
    }
}
