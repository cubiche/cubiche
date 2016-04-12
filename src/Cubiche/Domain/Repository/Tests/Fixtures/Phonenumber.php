<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Repository\Tests\Fixtures;

use Cubiche\Domain\Model\NativeValueObject;

/**
 * Phonenumber.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Phonenumber extends NativeValueObject
{
    /**
     * @var string
     */
    protected $number;

    /**
     * @param string $number
     *
     * @return static
     */
    public static function fromNative($number)
    {
        return new static($number);
    }

    /**
     * @param string $number
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($number)
    {
        if (\is_string($number) === false || empty($number)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid or empty. Allowed types for argument are "string".',
                $number
            ));
        }

        $this->number = $number;
    }

    /**
     * @return string
     */
    public function toNative()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function number()
    {
        return $this->number;
    }
}
