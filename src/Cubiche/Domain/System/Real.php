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
 * Real Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Real extends Number
{
    /**
     * @param float $value
     *
     * @return \Cubiche\Domain\System\Real
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
            $this->value = $value;
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
     */
    public function toReal()
    {
        return self::fromNative($this->value);
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
        return \is_infinite((float) $this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function isPositive()
    {
        return ((float) $this->value) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isNegative()
    {
        return ((float) $this->value) < 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isZero()
    {
        return ((float) $this->value) == 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function invertedAdd(Number $x)
    {
        return $x->addReal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function addInteger(Integer $x)
    {
        return $this->addReal($x->toReal());
    }

    /**
     * {@inheritdoc}
     */
    public function addReal(Real $x)
    {
        return new self($this->toNative() + $x->toNative());
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
        return $x->subReal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function subInteger(Integer $x)
    {
        return $this->subReal($x->toReal());
    }

    /**
     * {@inheritdoc}
     */
    public function subReal(Real $x)
    {
        return new self($this->toNative() - $x->toNative());
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
        return $x->multReal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function multInteger(Integer $x)
    {
        return $this->multReal($x->toReal());
    }

    /**
     * {@inheritdoc}
     */
    public function multReal(Real $x)
    {
        return new self($this->toNative() * $x->toNative());
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
        return $x->divReal($this);
    }

    /**
     * {@inheritdoc}
     */
    public function divInteger(Integer $x)
    {
        return $this->divReal($x->toReal());
    }

    /**
     * {@inheritdoc}
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
     */
    public function powReal(Real $x)
    {
        return self::fromNative(\pow($this->toNative(), $x->toNative()));
    }

    /**
     * {@inheritdoc}
     */
    public function powDecimal(Decimal $x, $scale = null)
    {
        return $this->powReal($x->toReal())->toDecimal();
    }

    /**
     * {@inheritdoc}
     */
    public function sqrt($scale = null)
    {
        if ($scale === null) {
            return self::fromNative(\sqrt($this->toNative()));
        }

        return $this->toDecimal()->sqrt($scale);
    }
}
