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

use Cubiche\Domain\System\Real;

/**
 * RealTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RealTests extends RealTestCase
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
        return (rand(1, 10) / 10) + 1;
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueNativeNumber()
    {
        return 20.32;
    }

    /**
     * {@inheritdoc}
     */
    protected function negativeNativeNumber()
    {
        return (rand(-1, -10) / 10) - 1;
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
        return Real::fromNative($value);
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
                    ->isInstanceOf(Real::class)
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
                    ->isInstanceOf(Real::class)
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
                    ->isInstanceOf(Real::class)
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
                    ->isInstanceOf(Real::class)
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
                $invalidScale = -1.34,
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
}
