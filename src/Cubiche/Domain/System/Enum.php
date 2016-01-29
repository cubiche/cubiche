<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\System;

use Cubiche\Domain\Model\NativeValueObjectInterface;
use MyCLabs\Enum\Enum as BaseEnum;

/**
 * Enum class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Enum extends BaseEnum implements NativeValueObjectInterface
{
    /**
     * @param mixed $value
     *
     * @return \Cubiche\Domain\System\Enum
     */
    public static function fromNative($value)
    {
        return new static($value);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function is($value)
    {
        return $this->value === $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\NativeValueObjectInterface::toNative()
     */
    public function toNative()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\EquatableInterface::equals()
     */
    public function equals($other)
    {
        return \get_class($this) === \get_class($other) && $this->getValue() === $other->getValue();
    }
}
