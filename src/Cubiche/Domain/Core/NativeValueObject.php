<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\Core;

/**
 * Abstract Native Value Object Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class NativeValueObject implements NativeValueObjectInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\EquatableInterface::equals()
     */
    public function equals($other)
    {
        return \get_class($this) === \get_class($other) && $this->toNative() == $other->toNative();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\ValueObjectInterface::__toString()
     */
    public function __toString()
    {
        return \strval($this->toNative());
    }
}
