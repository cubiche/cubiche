<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System;

/**
 * Real Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Real extends Number
{
    /**
     * @param float $value
     *
     * @return \Jadddp\Domain\System\Real
     */
    public static function fromNative($value)
    {
        return new static($value);
    }

    /**
     * @param float $value
     *
     * @throws \InvalidArgumentException
     */
    protected function __construct($value)
    {
        $validatedValue = \filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($validatedValue !== false) {
            $this->value = $validatedValue;
        } elseif (\is_infinite($value)) {
            $this->value = $value;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "float".',
                $value
            ));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::toInteger()
     */
    public function toInteger(RoundingMode $roundingMode = null)
    {
        if ($roundingMode === null) {
            $roundingMode = RoundingMode::HALF_UP();
        }

        return Integer::fromNative(\round($this->toNative(), 0, $roundingMode->toNative()));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::toReal()
     */
    public function toReal()
    {
        return self::fromNative($this->value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::toDecimal()
     */
    public function toDecimal()
    {
        return Decimal::fromNative($this->value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::isInfinite()
     */
    public function isInfinite()
    {
        return \is_infinite((float) $this->value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::isPositive()
     */
    public function isPositive()
    {
        return ((float) $this->value) > 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::isNegative()
     */
    public function isNegative()
    {
        return ((float) $this->value) < 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::isZero()
     */
    public function isZero()
    {
        return ((float) $this->value) == 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::invertedAdd()
     */
    protected function invertedAdd(Number $x)
    {
        return $x->addReal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::addInteger()
     */
    public function addInteger(Integer $x)
    {
        return $this->addReal($x->toReal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::addReal()
     */
    public function addReal(Real $x)
    {
        return new self($this->toNative() + $x->toNative());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::addDecimal()
     */
    public function addDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->addDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::invertedSub()
     */
    protected function invertedSub(Number $x)
    {
        return $x->subReal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::subInteger()
     */
    public function subInteger(Integer $x)
    {
        return $this->subReal($x->toReal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::subReal()
     */
    public function subReal(Real $x)
    {
        return new self($this->toNative() - $x->toNative());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::subDecimal()
     */
    public function subDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->subDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::invertedMult()
     */
    protected function invertedMult(Number $x)
    {
        return $x->multReal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::multInteger()
     */
    public function multInteger(Integer $x)
    {
        return $this->multReal($x->toReal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::multReal()
     */
    public function multReal(Real $x)
    {
        return new self($this->toNative() * $x->toNative());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::multDecimal()
     */
    public function multDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->multDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::invertedDiv()
     */
    protected function invertedDiv(Number $x)
    {
        return $x->divReal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::divInteger()
     */
    public function divInteger(Integer $x)
    {
        return $this->divReal($x->toReal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::divReal()
     */
    public function divReal(Real $x)
    {
        $value = $this->divSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        return new self($this->toNative() / $x->toNative());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::divDecimal()
     */
    public function divDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->divDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::invertedPow()
     */
    protected function invertedPow(Number $x)
    {
        return $x->powReal($this);
    }

    /**
     * @param int $x
     *
     * @return Number
     */
    public function powInteger(Integer $x)
    {
        return $this->powReal($x->toReal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::powReal()
     */
    public function powReal(Real $x)
    {
        return self::fromNative(\pow($this->toNative(), $x->toNative()));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::powDecimal()
     */
    public function powDecimal(Decimal $x, $scale = null)
    {
        return $this->toDecimal()->powDecimal($x, $scale);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Domain\System\Number::sqrt()
     */
    public function sqrt($scale = null)
    {
        if ($scale === null) {
            return self::fromNative(\sqrt($this->toNative()));
        }

        return $this->toDecimal()->sqrt($scale);
    }
}
