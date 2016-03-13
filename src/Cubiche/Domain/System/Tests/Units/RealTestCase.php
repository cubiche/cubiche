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
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;

/**
 * RealTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class RealTestCase extends NumberTestCase
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        \Closure $reflectionClassFactory = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );

        $this->getAssertionManager()
            ->setHandler(
                'positiveInfiniteNativeNumber',
                function () {
                    return $this->positiveInfiniteNativeNumber();
                }
            )
            ->setHandler(
                'negativeInfiniteNativeNumber',
                function () {
                    return $this->negativeInfiniteNativeNumber();
                }
            )
        ;
    }

    /**
     * @return mixed
     */
    abstract protected function positiveInfiniteNativeNumber();

    /**
     * @return mixed
     */
    abstract protected function negativeInfiniteNativeNumber();

    /*
     * Test isInfinite.
     */
    public function testIsInfinite()
    {
        parent::testIsInfinite();

        $this
            ->given(
                $positiveInfinite = $this->positiveInfiniteNativeNumber(),
                $number = $this->fromNative($positiveInfinite)
            )
            ->then
                ->boolean($number->isInfinite())
                    ->isTrue()
        ;

        $this
            ->given(
                $negativeInfinite = $this->negativeInfiniteNativeNumber(),
                $number = $this->fromNative($negativeInfinite)
            )
            ->then
                ->boolean($number->isInfinite())
                    ->isTrue()
        ;
    }

    /*
     * Test isPositive.
     */
    public function testIsPositive()
    {
        parent::testIsPositive();

        $this
            ->given(
                $positiveInfinite = $this->positiveInfiniteNativeNumber(),
                $number = $this->fromNative($positiveInfinite)
            )
            ->then
                ->boolean($number->isPositive())
                    ->isTrue()
        ;

        $this
            ->given(
                $negativeInfinite = $this->negativeInfiniteNativeNumber(),
                $number = $this->fromNative($negativeInfinite)
            )
            ->then
                ->boolean($number->isPositive())
                    ->isFalse()
        ;
    }

    /*
     * Test isNegative.
     */
    public function testIsNegative()
    {
        parent::testIsNegative();

        $this
            ->given(
                $positiveInfinite = $this->positiveInfiniteNativeNumber(),
                $number = $this->fromNative($positiveInfinite)
            )
            ->then
                ->boolean($number->isNegative())
                    ->isFalse()
        ;

        $this
            ->given(
                $negativeInfinite = $this->negativeInfiniteNativeNumber(),
                $number = $this->fromNative($negativeInfinite)
            )
            ->then
                ->boolean($number->isNegative())
                    ->isTrue()
        ;
    }

    /*
     * Test add.
     */
    public function testAdd()
    {
        parent::testAdd();

        $this
            ->given(
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $negativeInfinite = $this->fromNative($this->negativeInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean($number->add($positiveInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->add($number)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($number->add($negativeInfinite)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->add($number)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->add($positiveInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->add($negativeInfinite)->equals($negativeInfinite))
                    ->isTrue()
                ->exception(function () use ($positiveInfinite, $negativeInfinite) {
                    $positiveInfinite->add($negativeInfinite);
                })->isInstanceOf(\Exception::class)
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
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $negativeInfinite = $this->fromNative($this->negativeInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean($number->sub($positiveInfinite)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->sub($number)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($number->sub($negativeInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->sub($number)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->sub($negativeInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->sub($positiveInfinite)->equals($negativeInfinite))
                    ->isTrue()
                ->exception(function () use ($positiveInfinite) {
                    $positiveInfinite->sub($positiveInfinite);
                })->isInstanceOf(\Exception::class)
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
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $negativeInfinite = $this->fromNative($this->negativeInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber()),
                $negativeNumber = $this->fromNative($this->negativeNativeNumber())
            )
            ->then
                ->boolean($number->mult($positiveInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->mult($number)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($number->mult($negativeInfinite)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->mult($number)->equals($negativeInfinite))
                    ->isTrue()

                ->boolean($negativeNumber->mult($positiveInfinite)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->mult($negativeNumber)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($negativeNumber->mult($negativeInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->mult($negativeNumber)->equals($positiveInfinite))
                    ->isTrue()

                ->boolean($positiveInfinite->mult($positiveInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->mult($negativeInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->mult($negativeInfinite)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->mult($positiveInfinite)->equals($negativeInfinite))
                    ->isTrue()

                ->exception(function () use ($positiveInfinite) {
                    $zero = $this->fromNative(0);
                    $positiveInfinite->mult($zero);
                })->isInstanceOf(\Exception::class)
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
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $negativeInfinite = $this->fromNative($this->negativeInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber()),
                $negativeNumber = $this->fromNative($this->negativeNativeNumber())
            )
            ->then
                ->boolean($number->div($positiveInfinite)->isZero())
                    ->isTrue()
                ->boolean($number->div($negativeInfinite)->isZero())
                    ->isTrue()

                ->boolean($positiveInfinite->div($number)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->div($number)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->div($negativeNumber)->equals($negativeInfinite))
                    ->isTrue()
                ->boolean($negativeInfinite->div($negativeNumber)->equals($positiveInfinite))
                    ->isTrue()

                ->exception(function () use ($positiveInfinite) {
                    $positiveInfinite->div($positiveInfinite);
                })->isInstanceOf(\Exception::class)
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
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $negativeInfinite = $this->fromNative($this->negativeInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber()),
                $negativeNumber = $this->fromNative($this->negativeNativeNumber())
            )
            ->then
                ->boolean($number->pow($positiveInfinite)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($number->pow($negativeInfinite)->isZero())
                    ->isTrue()

                ->boolean($positiveInfinite->pow($number)->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->pow($number->toInteger())->equals($positiveInfinite))
                    ->isTrue()
                ->boolean($positiveInfinite->pow($number->toReal())->equals($positiveInfinite))
                    ->isTrue()

                ->boolean($positiveInfinite->pow($negativeNumber)->isZero())
                    ->isTrue()
                ->boolean($negativeInfinite->pow($negativeNumber)->isZero())
                    ->isTrue()

                ->boolean(
                    $negativeInfinite->pow(
                        $number->toInteger()->mult(Integer::fromNative(2))
                    )->equals($positiveInfinite)
                )->isTrue()
                ->boolean(
                    $negativeInfinite->pow(
                        $number->toInteger()->mult(Integer::fromNative(2))->inc()
                    )->equals($negativeInfinite)
                )->isTrue()
        ;
    }

    /*
     * Test compareTo.
     */
    public function testCompareTo()
    {
        parent::testCompareTo();

        $this
            ->given(
                $positiveInfinite = $this->fromNative($this->positiveInfiniteNativeNumber()),
                $negativeInfinite = $this->fromNative($this->negativeInfiniteNativeNumber()),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->variable($number->compareTo($positiveInfinite))
                    ->isEqualTo(-1)
                ->variable($number->compareTo($negativeInfinite))
                    ->isEqualTo(1)
                ->variable($negativeInfinite->compareTo($number))
                    ->isEqualTo(-1)
                ->variable($positiveInfinite->compareTo($number))
                    ->isEqualTo(1)
                ->variable($positiveInfinite->compareTo($negativeInfinite))
                    ->isEqualTo(1)
                ->variable($negativeInfinite->compareTo($positiveInfinite))
                    ->isEqualTo(-1)
                ->exception(function () use ($positiveInfinite) {
                    $positiveInfinite->compareTo($positiveInfinite);
                })->isInstanceOf(\Exception::class)
        ;
    }
}
