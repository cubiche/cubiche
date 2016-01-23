<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System;

use Cubiche\Domain\Core\NativeValueObjectInterface;

/**
 * Abstract Number Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Number implements NativeValueObjectInterface
{
    /**
     * @var float|int|string
     */
    protected $value;

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

    /**
     * @param RoundingMode $roundingMode
     *
     * @return \Cubiche\Domain\Async\Integer
     */
    abstract public function toInteger(RoundingMode $roundingMode = null);

    /**
     * @return \Cubiche\Domain\System\Real
     */
    abstract public function toReal();

    /**
     * @return Decimal
     */
    abstract public function toDecimal();

    /**
     * @return bool
     */
    abstract public function isInfinite();

    /**
     * @return bool
     */
    abstract public function isPositive();

    /**
     * @return bool
     */
    abstract public function isNegative();

    /**
     * @return bool
     */
    abstract public function isZero();

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    public function add(Number $x)
    {
        return $x->invertedAdd($this);
    }

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract protected function invertedAdd(Number $x);

    /**
     * @param \Cubiche\Domain\System\Integer $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract public function addInteger(Integer $x);

    /**
     * @param \Cubiche\Domain\System\Real $x
     *
     * @return \Cubiche\Domain\System\Real
     */
    abstract public function addReal(Real $x);

    /**
     * @param Decimal $x
     * @param int     $scale
     *
     * @return Decimal
     */
    abstract public function addDecimal(Decimal $x, $scale = null);

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    public function sub(Number $x)
    {
        return $x->invertedSub($this);
    }

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract protected function invertedSub(Number $x);

    /**
     * @param \Cubiche\Domain\System\Integer $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract public function subInteger(Integer $x);

    /**
     * @param \Cubiche\Domain\System\Real $x
     *
     * @return \Cubiche\Domain\System\Real
     */
    abstract public function subReal(Real $x);

    /**
     * @param Decimal $x
     *
     * @return Decimal
     */
    abstract public function subDecimal(Decimal $x);

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    public function mult(Number $x)
    {
        return $x->invertedMult($this);
    }

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract protected function invertedMult(Number $x);

    /**
     * @param \Cubiche\Domain\System\Integer $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract public function multInteger(Integer $x);

    /**
     * @param \Cubiche\Domain\System\Real $x
     *
     * @return \Cubiche\Domain\System\Real
     */
    abstract public function multReal(Real $x);

    /**
     * @param Decimal $x
     * @param int     $scale
     *
     * @return Decimal
     */
    abstract public function multDecimal(Decimal $x, $scale = null);

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    public function div(Number $x)
    {
        return $x->invertedDiv($this);
    }

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract protected function invertedDiv(Number $x);

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @throws \DomainException
     *
     * @return static|null
     */
    protected function divSpecialCases(Number $x)
    {
        if ($x->isZero()) {
            throw new \DomainException('Division by zero is not allowed.');
        }

        if (($this->isZero() || $x->isInfinite()) && !$this->isInfinite()) {
            return static::fromNative(0);
        }

        return;
    }

    /**
     * @param \Cubiche\Domain\System\Integer $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract public function divInteger(Integer $x);

    /**
     * @param \Cubiche\Domain\System\Real $x
     *
     * @return \Cubiche\Domain\System\Real
     */
    abstract public function divReal(Real $x);

    /**
     * @param Decimal $x
     * @param int     $scale
     *
     * @return Decimal
     */
    abstract public function divDecimal(Decimal $x, $scale = null);

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    public function pow(Number $x)
    {
        return $x->invertedPow($this);
    }

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract protected function invertedPow(Number $x);

    /**
     * @param \Cubiche\Domain\System\Integer $x
     *
     * @return \Cubiche\Domain\System\Number
     */
    abstract public function powInteger(Integer $x);

    /**
     * @param \Cubiche\Domain\System\Real $x
     *
     * @return \Cubiche\Domain\System\Real
     */
    abstract public function powReal(Real $x);

    /**
     * @param Decimal $x
     * @param int     $scale
     *
     * @return Decimal
     */
    abstract public function powDecimal(Decimal $x, $scale = null);

    /**
     * @param string $scale
     *
     * @return \Cubiche\Domain\System\Real
     */
    abstract public function sqrt($scale = null);

    /**
     * @param \Cubiche\Domain\System\Number $x
     *
     * @return int
     */
    public function compareTo(Number $x)
    {
        $value = $this->sub($x);

        return $value->isZero() ? 0 : $value->isPositive() ? 1 : -1;
    }
}
