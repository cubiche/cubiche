<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System;

use Cubiche\Domain\Core\NativeValueObjectInterface;
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
     * @see \Cubiche\Domain\Core\NativeValueObjectInterface::toNative()
     */
    public function toNative()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\ValueObjectInterface::equals()
     */
    public function equals($other)
    {
        return \get_class($this) === \get_class($other) && $this->getValue() === $other->getValue();
    }
}
