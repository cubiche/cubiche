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
     */
    protected function invertedAdd(Number $x)
    {
        return $x->addDecimal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function addInteger(Integer $x)
    {
        return $this->addDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     */
    public function addReal(Real $x)
    {
        return $this->addDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
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
     */
    protected function invertedSub(Number $x)
    {
        return $x->subDecimal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function subInteger(Integer $x)
    {
        return $this->subDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     */
    public function subReal(Real $x)
    {
        return $this->subDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
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
     */
    protected function invertedMult(Number $x)
    {
        return $x->multDecimal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function multInteger(Integer $x)
    {
        return $this->multDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     */
    public function multReal(Real $x)
    {
        return $this->multDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
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
     */
    protected function invertedDiv(Number $x)
    {
        return $x->divDecimal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function divInteger(Integer $x)
    {
        return $this->divDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
     */
    public function divReal(Real $x)
    {
        return $this->divDecimal($x->toDecimal());
    }

    /**
     * {@inheritdoc}
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
     */
    protected function invertedPow(Number $x)
    {
        return $x->powDecimal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function powInteger(Integer $x)
    {
        return new self(\bcpow($this->toNative(), $x->toNative(), $this->scale(null)));
    }

    /**
     * {@inheritdoc}
     */
    public function sqrt($scale = null)
    {
        return new self(\bcsqrt($this->toNative(), $this->scale($scale)));
    }
}
