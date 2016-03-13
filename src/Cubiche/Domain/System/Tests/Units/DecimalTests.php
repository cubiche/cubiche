<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\System\Tests\Units;

use Cubiche\Domain\Exception\NotImplementedException;
use Cubiche\Domain\System\Decimal;
use Cubiche\Domain\System\DecimalInfinite;

/**
 * DecimalTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DecimalTests extends RealTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function positiveInfiniteNativeNumber()
    {
        return INF;
    }

    /**
     * {@inheritdoc}
     */
    protected function negativeInfiniteNativeNumber()
    {
        return -INF;
    }

    /**
     * {@inheritdoc}
     */
    protected function randomNativeNumber()
    {
        return (string) (rand(1, 10) / 10) + 4;
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueNativeNumber()
    {
        return '7.64';
    }

    /**
     * {@inheritdoc}
     */
    protected function negativeNativeNumber()
    {
        return (string) (rand(-1, -10) / 10) - 4;
    }

    /**
     * {@inheritdoc}
     */
    protected function invalidNativeNumber()
    {
        return NAN;
    }

    /**
     * {@inheritdoc}
     */
    protected function fromNative($value)
    {
        return Decimal::fromNative($value);
    }

    /*
     * Test add.
     */
    public function testAdd()
    {
        parent::testAdd();

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->add($b->toInteger()))
            ->then
                ->object($c)
                    ->isInstanceOf(Decimal::class)
        ;

        $this
            ->given(
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean($positiveInfinite->addInteger($number->toInteger())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->addReal($number->toReal())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->addDecimal($number->toDecimal())->equals($positiveInfinite))
                    ->isTrue()
        ;
    }

    /*
     * Test sub.
     */
    public function testSub()
    {
        parent::testSub();

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->sub($b->toInteger()))
            ->then
                ->object($c)
                    ->isInstanceOf(Decimal::class)
        ;

        $this
            ->given(
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
               ->boolean($positiveInfinite->subInteger($number->toInteger())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->subReal($number->toReal())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->subDecimal($number->toDecimal())->equals($positiveInfinite))
                    ->isTrue()
        ;
    }

    /*
     * Test mult.
     */
    public function testMult()
    {
        parent::testMult();

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->mult($b->toInteger()))
            ->then
                ->object($c)
                    ->isInstanceOf(Decimal::class)
        ;

        $this
            ->given(
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean($positiveInfinite->multInteger($number->toInteger())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->multReal($number->toReal())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->multDecimal($number->toDecimal())->equals($positiveInfinite))
                    ->isTrue()
        ;
    }

    /*
     * Test div.
     */
    public function testDiv()
    {
        parent::testDiv();

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->div($b->toInteger()))
            ->then
                ->object($c)
                    ->isInstanceOf(Decimal::class)
        ;

        $this
            ->given(
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean($positiveInfinite->divInteger($number->toInteger())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->divReal($number->toReal())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->divDecimal($number->toDecimal())->equals($positiveInfinite))
                    ->isTrue()
        ;
    }

    /*
     * Test pow.
     */
    public function testPow()
    {
        parent::testPow();

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->pow($b))
            ->then
                ->object($c)
                    ->isInstanceOf(Decimal::class)
        ;

        $this
            ->given(
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $negativeInfinite = $this->fromNative($this->negativeInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber()),
                $zero = $this->fromNative(0)
            )
            ->then
                ->exception(function () use ($positiveInfinite, $zero) {
                    $positiveInfinite->pow($zero);
                })->isInstanceOf(NotImplementedException::class)
                ->exception(function () use ($positiveInfinite, $negativeInfinite) {
                    $negativeInfinite->pow($positiveInfinite);
                })->isInstanceOf(\DomainException::class)
                ->exception(function () use ($negativeInfinite, $number) {
                    $negativeInfinite->powReal($number->toReal());
                })->isInstanceOf(NotImplementedException::class)
        ;
    }

    /*
     * Test sqrt.
     */
    public function testSqrt()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $invalidScale = 2.56,
                $a = $this->fromNative($native),
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber())
            )
            ->when(
                $b = $a->sqrt(),
                $c = $a->sqrt(2)
            )
            ->then
                ->object($b)
                    ->isInstanceOf(Decimal::class)
                ->variable($b->toNative())
                    ->isEqualTo(\bcsqrt($native, Decimal::defaultScale()))
                ->variable($c->toNative())
                    ->isEqualTo(\bcsqrt($native, 2))
                ->exception(function () use ($a, $invalidScale) {
                    $a->sqrt($invalidScale);
                })->isInstanceOf(\InvalidArgumentException::class)
                ->exception(function () use ($positiveInfinite) {
                    $positiveInfinite->sqrt();
                })->isInstanceOf(NotImplementedException::class)
        ;
    }

    /*
     * Test setDefaultScale.
     */
    public function testSetDefaultScale()
    {
        $this
            ->if($scale = 8)
            ->when(Decimal::setDefaultScale($scale))
            ->then
                ->variable(Decimal::defaultScale())
                    ->isEqualTo($scale)
            ->if($invalidScale = 8.1)
            ->then
                ->exception(function () use ($invalidScale) {
                    Decimal::setDefaultScale($invalidScale);
                })->isInstanceOf(\InvalidArgumentException::class)
            ->if($negativeScale = -2)
            ->then
                ->exception(function () use ($negativeScale) {
                    Decimal::setDefaultScale($negativeScale);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /*
     * Test infiniteFromNative.
     */
    public function testInfiniteFromNative()
    {
        $this
            ->if($number = $this->randomNativeNumber())
            ->then
                ->exception(function () use ($number) {
                    DecimalInfinite::fromNative($number);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
