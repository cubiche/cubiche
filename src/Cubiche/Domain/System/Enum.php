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

use Cubiche\Core\Enum\Enum as BaseEnum;
use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * Enum Class.
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
        try {
            return new static($value);
        } catch (\UnexpectedValueException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode());
        }
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
}
