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

use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\Real;

/**
 * IntegerTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class IntegerTests extends NumberTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomNativeNumber()
    {
        return rand(1, 10);
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueNativeNumber()
    {
        return 20;
    }

    /**
     * {@inheritdoc}
     */
    protected function negativeNativeNumber()
    {
        return rand(-1, -10);
    }

    /**
     * {@inheritdoc}
     */
    protected function invalidNativeNumber()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    protected function fromNative($value)
    {
        return Integer::fromNative($value);
    }

    /*
     * Test sqrt.
     */
    public function testSqrt()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $invalidScale = 2.4,
                $a = $this->fromNative($native)
            )
            ->when(
                $b = $a->sqrt(),
                $c = $a->sqrt(2)
            )
            ->then
                ->object($b)
                    ->isInstanceOf(Real::class)
                ->variable($b->toNative())
                    ->isEqualTo(\sqrt($native))
                ->variable($c->toNative())
                    ->isEqualTo(\bcsqrt($native, 2))
                ->exception(function () use ($a, $invalidScale) {
                    $a->sqrt($invalidScale);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /*
     * Test inc.
     */
    public function testInc()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $one = $this->fromNative(1),
                $a = $this->fromNative($native)
            )
            ->when($b = $a->inc())
            ->then
                ->boolean($b->equals($a->add($one)))
                ->isTrue()
        ;
    }

    /*
     * Test dec.
     */
    public function testDec()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $one = $this->fromNative(1),
                $a = $this->fromNative($native)
            )
            ->when($b = $a->dec())
            ->then
                ->boolean($b->equals($a->sub($one)))
                ->isTrue()
        ;
    }

    /*
     * Test mod.
     */
    public function testMod()
    {
        $this
            ->given(
                $native1 = $this->randomNativeNumber(),
                $native2 = $this->randomNativeNumber(),
                $number1 = $this->fromNative($native1),
                $number2 = $this->fromNative($native2)
            )
            ->when($c = $number1->mod($number2))
            ->then
                ->variable($c->toNative())
                    ->isEqualTo($native1 % $native2)
        ;
    }

    /*
     * Test isEven.
     */
    public function testIsEven()
    {
        $this
            ->given(
                $number1 = $this->fromNative(2),
                $number2 = $this->fromNative(3)
            )
            ->then
                ->boolean($number1->isEven())
                    ->isTrue()
                ->boolean($number2->isEven())
                    ->isFalse()
        ;
    }

    /*
     * Test isOdd.
     */
    public function testIsOdd()
    {
        $this
            ->given(
                $number1 = $this->fromNative(2),
                $number2 = $this->fromNative(3)
            )
            ->then
                ->boolean($number1->isOdd())
                    ->isFalse()
                ->boolean($number2->isOdd())
                    ->isTrue()
        ;
    }
}
