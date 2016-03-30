<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Geolocation;

use Cubiche\Domain\Comparable\ComparableInterface;
use Cubiche\Domain\Model\ValueObjectInterface;
use Cubiche\Domain\System\Real;

/**
 * Distance Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Distance implements ValueObjectInterface, ComparableInterface
{
    /**
     * @var DistanceUnit
     */
    protected $unit;

    /**
     * @var \Cubiche\Domain\System\Real
     */
    protected $value;

    /**
     * @param Coordinate   $from
     * @param Coordinate   $to
     * @param DistanceUnit $unit
     *
     * @return Distance
     */
    public static function fromTo(
        Coordinate $from,
        Coordinate $to,
        DistanceUnit $unit = null
    ) {
        return $from->distance($to, $unit);
    }

    /**
     * @param \Cubiche\Domain\System\Real $value
     * @param DistanceUnit                $unit
     */
    public function __construct(Real $value, DistanceUnit $unit)
    {
        $this->value = $value;
        $this->unit = $unit;
    }

    /**
     * @return DistanceUnit
     */
    public function unit()
    {
        return $this->unit;
    }

    /**
     * @return \Cubiche\Domain\System\Real
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @param DistanceUnit $unit
     *
     * @return Distance
     */
    public function in(DistanceUnit $unit)
    {
        if ($this->unit()->equals($unit)) {
            return $this;
        }

        return new self($this->value()->mult(Real::fromNative($this->unit()->conversionRate($unit))), $unit);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Equatable\EquatableInterface::equals()
     */
    public function equals($other)
    {
        return $other instanceof self &&
            $this->unit()->equals($other->unit()) &&
            $this->value()->equals($other->value());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparableInterface::compareTo()
     */
    public function compareTo($other)
    {
        if (!$other instanceof self) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "%s".',
                $other,
                self::class
            ));
        }

        return $this->value()->compareTo($other->in($this->unit())->value());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return\sprintf('%F %s', $this->value()->toNative(), $this->unit()->toNative());
    }
}
