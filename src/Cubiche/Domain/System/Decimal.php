<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System;

/**
 * Decimal Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Decimal extends Real
{
    /**
     * @var int
     */
    protected static $defaultScale = 12;

    /**
     * @param int $scale
     *
     * @throws \InvalidArgumentException
     */
    public static function setDefaultScale($scale)
    {
        if (!is_int($scale) || $scale < 0) {
            throw new \InvalidArgumentException('The scale must be a positive integer');
        }

        self::$defaultScale = $scale;
        \bcscale(self::$defaultScale);
    }

    /**
     * @return int
     */
    public static function defaultScale()
    {
        return self::$defaultScale;
    }

    /**
     * @param float|int|string $value
     *
     * @return Decimal
     */
    public static function fromNative($value)
    {
        if (\is_infinite((float) $value)) {
            return DecimalInfinite::fromNative($value);
        }

        return new self($value);
    }

    /**
     * @param string $value
     */
    protected function __construct($value)
    {
        parent::__construct($value);
        if ($this->isInfinite()) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "float".',
                $value
            ));
        }
    }

    /**
     * @param int $scale
     *
     * @return int
     */
    protected function scale($scale)
    {
        if ($scale !== null && (!is_int($scale) || $scale < 0)) {
            throw new \InvalidArgumentException('The scale must be a positive integer');
        }

        return $scale === null ? self::$defaultScale : $scale;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::invertedAdd()
     */
    protected function invertedAdd(Number $x)
    {
        return $x->addDecimal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::addInteger()
     */
    public function addInteger(Integer $x)
    {
        return $this->addDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::addReal()
     */
    public function addReal(Real $x)
    {
        return $this->addDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::addDecimal()
     */
    public function addDecimal(Decimal $x, $scale = null)
    {
        if ($x->isInfinite()) {
            return $x;
        }

        return new self(\bcadd($this->toNative(), $x->toNative(), $this->scale($scale)));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Real::invertedSub()
     */
    protected function invertedSub(Number $x)
    {
        return $x->subDecimal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::subInteger()
     */
    public function subInteger(Integer $x)
    {
        return $this->subDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::subReal()
     */
    public function subReal(Real $x)
    {
        return $this->subDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::subDecimal()
     */
    public function subDecimal(Decimal $x, $scale = null)
    {
        if ($x->isInfinite()) {
            return $x->isPositive() ? DecimalInfinite::infNegative() : DecimalInfinite::infPositive();
        }

        return new self(\bcsub($this->toNative(), $x->toNative(), $this->scale($scale)));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::invertedMult()
     */
    protected function invertedMult(Number $x)
    {
        return $x->multDecimal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::multInteger()
     */
    public function multInteger(Integer $x)
    {
        return $this->multDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::multReal()
     */
    public function multReal(Real $x)
    {
        return $this->multDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::multDecimal()
     */
    public function multDecimal(Decimal $x, $scale = null)
    {
        if ($x->isInfinite()) {
            return $x->multDecimal($this);
        }

        return new self(\bcmul($this->toNative(), $x->toNative(), $this->scale($scale)));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Real::invertedDiv()
     */
    protected function invertedDiv(Number $x)
    {
        return $x->divDecimal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::divInteger()
     */
    public function divInteger(Integer $x)
    {
        return $this->divDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::divReal()
     */
    public function divReal(Real $x)
    {
        return $this->divDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::divDecimal()
     */
    public function divDecimal(Decimal $x, $scale = null)
    {
        $value = $this->divSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        return new self(\bcdiv($this->toNative(), $x->toNative(), $this->scale($scale)));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Real::invertedPow()
     */
    protected function invertedPow(Number $x)
    {
        return $x->powDecimal($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Real::powInteger()
     */
    public function powInteger(Integer $x)
    {
        return $this->powDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::powReal()
     */
    public function powReal(Real $x)
    {
        return $this->powDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::powDecimal()
     */
    public function powDecimal(Decimal $x, $scale = null)
    {
        return new self(\bcpow($this->toNative(), $x->toNative(), $this->scale($scale)));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::sqrt()
     */
    public function sqrt($scale = null)
    {
        return new self(\bcsqrt($this->toNative(), $this->scale($scale)));
    }
}
