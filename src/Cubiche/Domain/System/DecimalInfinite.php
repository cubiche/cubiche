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

use Cubiche\Domain\Exception\NotImplementedException;

/**
 * Decimal Infinite Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DecimalInfinite extends Decimal
{
    /**
     * @var DecimalInfinite
     */
    protected static $infPositive = null;

    /**
     * @var DecimalInfinite
     */
    protected static $infNegative = null;

    /**
     * @param float $value
     *
     * @return \Cubiche\Domain\System\DecimalInfinite
     */
    public static function fromNative($value)
    {
        if ($value === INF) {
            return self::infPositive();
        } elseif ($value === -INF) {
            return self::infNegative();
        }

        return new self($value);
    }

    /**
     * @return \Cubiche\Domain\System\DecimalInfinite
     */
    public static function infPositive()
    {
        if (self::$infPositive === null) {
            self::$infPositive = new self(INF);
        }

        return self::$infPositive;
    }

    /**
     * @return \Cubiche\Domain\System\DecimalInfinite
     */
    public static function infNegative()
    {
        if (self::$infNegative === null) {
            self::$infNegative = new self(-INF);
        }

        return self::$infNegative;
    }

    /**
     * @param string $value
     */
    protected function __construct($value)
    {
        if (\is_infinite($value)) {
            $this->value = $value;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "INF" or "-INF".',
                $value
            ));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::add()
     */
    public function add(Number $x)
    {
        if (!$x->isInfinite() ||
            ($this->isPositive() && $x->isPositive()) ||
            ($this->isNegative() && $x->isNegative())) {
            return $this;
        }

        throw new \DomainException('The add operation is not defined between "INF" and "-INF"');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::addInteger()
     */
    public function addInteger(Integer $x)
    {
        return $this->add($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::addReal()
     */
    public function addReal(Real $x)
    {
        return $this->add($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::addDecimal()
     */
    public function addDecimal(Decimal $x, $scale = null)
    {
        return $this->add($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::sub()
     */
    public function sub(Number $x)
    {
        if (!$x->isInfinite() ||
            ($this->isPositive() && $x->isNegative()) ||
            ($this->isNegative() && $x->isPositive())) {
            return $this;
        }

        throw new \DomainException('The sub operation is not defined between "INF" and "INF"');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::subInteger()
     */
    public function subInteger(Integer $x)
    {
        return $this->sub($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::subReal()
     */
    public function subReal(Real $x)
    {
        return $this->sub($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::subDecimal()
     */
    public function subDecimal(Decimal $x, $scale = null)
    {
        return $this->sub($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::mult()
     */
    public function mult(Number $x)
    {
        if ($x->isZero()) {
            throw new \DomainException('The mult operation is not defined between "INF" and zero');
        }

        if ($this->isPositive() === $x->isPositive()) {
            return self::infPositive();
        }

        return self::infNegative();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::multInteger()
     */
    public function multInteger(Integer $x)
    {
        return $this->mult($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::multReal()
     */
    public function multReal(Real $x)
    {
        return $this->mult($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::multDecimal()
     */
    public function multDecimal(Decimal $x, $scale = null)
    {
        return $this->mult($x);
    }

    protected function divSpecialCases(Number $x)
    {
        if ($x->isInfinite()) {
            throw new \DomainException('The div operation is not defined between "INF" and "INF"');
        }

        return parent::divSpecialCases($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::div()
     */
    public function div(Number $x)
    {
        $this->divSpecialCases($x);

        if ($this->isPositive() === $x->isPositive()) {
            return self::infPositive();
        }

        return self::infNegative();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::divInteger()
     */
    public function divInteger(Integer $x)
    {
        return $this->div($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::divReal()
     */
    public function divReal(Real $x)
    {
        return $this->div($x);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::divDecimal()
     */
    public function divDecimal(Decimal $x, $scale = null)
    {
        return $this->div($x);
    }

    /**
     * @param Number $x
     *
     * @throws \DomainException
     *
     * @return \Cubiche\Domain\System\DecimalInfinite
     */
    protected function powSpecialCases(Number $x)
    {
        if ($x->isPositive()) {
            if ($this->isPositive()) {
                return $this;
            }

            if ($x->isInfinite()) {
                throw new \DomainException('The pow operation is not defined between "-INF" and "INF"');
            }
        } elseif ($x->isNegative()) {
            return Decimal::fromNative(0);
        }

        return;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::powInteger()
     */
    public function powInteger(Integer $x)
    {
        $value = $this->powSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        if ($x->isEven()) {
            return self::infPositive();
        }

        return self::infNegative();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::powReal()
     */
    public function powReal(Real $x)
    {
        $value = $this->powSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        throw new NotImplementedException(self::class, 'powReal');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Decimal::powDecimal()
     */
    public function powDecimal(Decimal $x, $scale = null)
    {
        $value = $this->powSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        throw new NotImplementedException(self::class, 'powDecimal');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Number::sqrt()
     */
    public function sqrt($scale = null)
    {
        throw new NotImplementedException(self::class, 'sqrt');
    }
}
