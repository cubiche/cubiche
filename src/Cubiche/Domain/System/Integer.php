<?php

/**
 * This file is part of the cubiche/cubiche project.
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
     *
     * @see \Cubiche\Domain\System\Number::toInteger()
     */
    public function toInteger(RoundingMode $roundingMode = null)
    {
        return new self($this->value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::toReal()
     */
    public function toReal()
    {
        return Real::fromNative($this->value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::toDecimal()
     */
    public function toDecimal()
    {
        return Decimal::fromNative($this->value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::isInfinite()
     */
    public function isInfinite()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::isPositive()
     */
    public function isPositive()
    {
        return $this->value > 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::isNegative()
     */
    public function isNegative()
    {
        return $this->value < 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::isZero()
     */
    public function isZero()
    {
        return $this->value === 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::invertedAdd()
     */
    protected function invertedAdd(Number $x)
    {
        return $x->addInteger($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::addInteger()
     */
    public function addInteger(Integer $x)
    {
        return new static($this->toNative() + $x->toNative());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::addReal()
     */
    public function addReal(Real $x)
    {
        return $this->toReal()->addReal($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::addDecimal()
     */
    public function addDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->addDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::invertedSub()
     */
    protected function invertedSub(Number $x)
    {
        return $x->subInteger($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::subInteger()
     */
    public function subInteger(Integer $x)
    {
        return new static($this->toNative() - $x->toNative());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::subReal()
     */
    public function subReal(Real $x)
    {
        return $this->toReal()->subReal($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::subDecimal()
     */
    public function subDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->subDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::invertedMult()
     */
    protected function invertedMult(Number $x)
    {
        return $x->multInteger($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::multInteger()
     */
    public function multInteger(Integer $x)
    {
        return new static($this->toNative() * $x->toNative());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::multReal()
     */
    public function multReal(Real $x)
    {
        return $this->toReal()->multReal($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::multDecimal()
     */
    public function multDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->multDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::invertedDiv()
     */
    protected function invertedDiv(Number $x)
    {
        return $x->divInteger($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::divInteger()
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
     *
     * @see \Cubiche\Domain\System\Number::divReal()
     */
    public function divReal(Real $x)
    {
        return $this->toReal()->divReal($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::divDecimal()
     */
    public function divDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->divDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::invertedPow()
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
     *
     * @see \Cubiche\Domain\System\Number::powReal()
     */
    public function powReal(Real $x)
    {
        return $this->toReal()->powReal($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::powDecimal()
     */
    public function powDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->powDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::sqrt()
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
