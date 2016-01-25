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

use Cubiche\Domain\Core\NativeValueObject;

/**
 * String Literal Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class StringLiteral extends NativeValueObject
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     *
     * @return static
     */
    public static function fromNative($value)
    {
        return new static($value);
    }

    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($value)
    {
        if (\is_string($value) === false) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "string".',
                $value
            ));
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function toNative()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return \strlen($this->toNative()) === 0;
    }
}
