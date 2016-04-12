<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Enum;

use Cubiche\Core\Equatable\EquatableInterface;
use MyCLabs\Enum\Enum as BaseEnum;

/**
 * Enum class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Enum extends BaseEnum implements EquatableInterface
{
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
     * @see \Cubiche\Core\Equatable\EquatableInterface::equals()
     */
    public function equals($other)
    {
        return \get_class($this) === \get_class($other) && $this->getValue() === $other->getValue();
    }
}
