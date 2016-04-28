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

/**
 * Integer Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Integer extends Number
{
    /**
     * @param int $value
     *
     * @return \Cubiche\Domain\System\Integer
     */
    public static function fromNative($value)
    {
        return new static($value);
    }

    /**
     * @param int $value
     *
     * @throws \InvalidArgumentException
     */
    protected function __construct($value)
    {
        $filteredValue = \filter_var($value, FILTER_VALIDATE_INT);
        if ($filteredValue === false) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "int".',
                $value
            ));
        }

        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function toInteger(RoundingMode $roundingMode = null)
    {
        return new self($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function toReal()
    {
        return Real::fromNative($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function toDecimal()
    {
        return Decimal::fromNative($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function isInfinite()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isPositive()
    {
        return $this->value > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isNegative()
    {
        return $this->value < 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isZero()
    {
        return $this->value === 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function invertedAdd(Number $x)
    {
        return $x->addInteger($this);
    }

    /**
     * {@inheritdoc}
     */
    public function addInteger(Integer $x)
    {
        return new static($this->toNative() + $x->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function addReal(Real $x)
    {
        return $this->toReal()->addReal($x);
    }

    /**
     * {@inheritdoc}
     */
    public function addDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->addDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     */
    protected function invertedSub(Number $x)
    {
        return $x->subInteger($this);
    }

    /**
     * {@inheritdoc}
     */
    public function subInteger(Integer $x)
    {
        return new static($this->toNative() - $x->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function subReal(Real $x)
    {
        return $this->toReal()->subReal($x);
    }

    /**
     * {@inheritdoc}
     */
    public function subDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->subDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     */
    protected function invertedMult(Number $x)
    {
        return $x->multInteger($this);
    }

    /**
     * {@inheritdoc}
     */
    public function multInteger(Integer $x)
    {
        return new static($this->toNative() * $x->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function multReal(Real $x)
    {
        return $this->toReal()->multReal($x);
    }

    /**
     * {@inheritdoc}
     */
    public function multDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->multDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     */
    protected function invertedDiv(Number $x)
    {
        return $x->divInteger($this);
    }

    /**
     * {@inheritdoc}
     */
    public function divInteger(Integer $x)
    {
        $value = $this->divSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        return Real::fromNative($this->toNative() / $x->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function divReal(Real $x)
    {
        return $this->toReal()->divReal($x);
    }

    /**
     * {@inheritdoc}
     */
    public function divDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->divDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     */
    protected function invertedPow(Number $x)
    {
        return $x->powInteger($this);
    }

    /**
     * @param int $x
     *
     * @return Number
     */
    public function powInteger(Integer $x)
    {
        return new static(\pow($this->toNative(), $x->toNative()));
    }

    /**
     * {@inheritdoc}
     */
    public function powReal(Real $x)
    {
        return $this->toReal()->powReal($x);
    }

    /**
     * {@inheritdoc}
     */
    public function powDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->powDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     */
    public function sqrt($scale = null)
    {
        return $this->toReal()->sqrt($scale);
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function inc()
    {
        return new static($this->toNative() + 1);
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function dec()
    {
        return new static($this->toNative() - 1);
    }

    /**
     * @param int $x
     *
     * @return \Cubiche\Domain\System\Integer
     */
    public function mod(Integer $x)
    {
        return new static($this->toNative() % $x->toNative());
    }

    /**
     * @return bool
     */
    public function isEven()
    {
        return $this->mod(self::fromNative(2))->isZero();
    }

    /**
     * @return bool
     */
    public function isOdd()
    {
        return !$this->isEven();
    }
}
