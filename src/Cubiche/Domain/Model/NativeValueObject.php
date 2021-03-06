<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model;

/**
 * Abstract Native Value Object Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class NativeValueObject implements NativeValueObjectInterface
{
    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return \get_class($this) === \get_class($other) && $this->toNative() == $other->toNative();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return \strval($this->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->__toString();
    }
}
