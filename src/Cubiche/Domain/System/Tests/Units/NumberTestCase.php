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

use Closure;
use Cubiche\Domain\Model\Tests\Units\NativeValueObjectTestCase;
use Cubiche\Domain\System\Decimal;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\Number;
use Cubiche\Domain\System\Real;
use Cubiche\Domain\System\RoundingMode;
use Cubiche\Domain\System\StringLiteral;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;

/**
 * NumberTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class NumberTestCase extends NativeValueObjectTestCase
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        Closure $reflectionClassFactory = null
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
                'randomNativeNumber',
                function () {
                    return $this->randomNativeNumber();
                }
            )
            ->setHandler(
                'invalidNativeNumber',
                function () {
                    return $this->invalidNativeNumber();
                }
            )
            ->setHandler(
                'uniqueNativeNumber',
                function () {
                    return $this->uniqueNativeNumber();
                }
            )
            ->setHandler(
                'negativeNativeNumber',
                function () {
                    return $this->negativeNativeNumber();
                }
            )
        ;
    }

    /**
     * @return mixed
     */
    abstract protected function randomNativeNumber();

    /**
     * @return mixed
     */
    abstract protected function invalidNativeNumber();

    /**
     * @return mixed
     */
    abstract protected function uniqueNativeNumber();

    /**
     * @return mixed
     */
    abstract protected function negativeNativeNumber();

    /**
     * {@inheritdoc}
     */
    protected function randomNativeValue()
    {
        return$this->randomNativeNumber();
    }

    /**
     * {@inheritdoc}
     */
    protected function invalidNativeValue()
    {
        return $this->invalidNativeNumber();
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueNativeValue()
    {
        return $this->uniqueNativeNumber();
    }

    /*
     * Test fromNative/toNative.
     */
    public function testFromNativeToNative()
    {
        parent::testFromNativeToNative();

        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->then
                ->object($number)
                    ->isInstanceOf(Number::class)
        ;
    }

    /*
     * Test toInteger.
     */
    public function testToInteger()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->when($integer = $number->toInteger())
            ->then
                ->object($integer)
                    ->isInstanceOf(Integer::class)
                ->variable($integer->toNative())
                    ->isEqualTo(\round($native, 0, PHP_ROUND_HALF_UP))
        ;

        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->when($integer = $number->toInteger(RoundingMode::HALF_UP()))
            ->then
                ->object($integer)
                    ->isInstanceOf(Integer::class)
                ->variable($integer->toNative())
                    ->isEqualTo(\round($native, 0, PHP_ROUND_HALF_UP))
        ;

        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->when($integer = $number->toInteger(RoundingMode::HALF_DOWN()))
            ->then
                ->object($integer)
                    ->isInstanceOf(Integer::class)
                ->variable($integer->toNative())
                    ->isEqualTo(\round($native, 0, PHP_ROUND_HALF_DOWN))
        ;

        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->when($integer = $number->toInteger(RoundingMode::HALF_EVEN()))
            ->then
                ->object($integer)
                    ->isInstanceOf(Integer::class)
                ->variable($integer->toNative())
                    ->isEqualTo(\round($native, 0, PHP_ROUND_HALF_EVEN))
        ;

        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->when($integer = $number->toInteger(RoundingMode::HALF_ODD()))
            ->then
                ->object($integer)
                    ->isInstanceOf(Integer::class)
                ->variable($integer->toNative())
                    ->isEqualTo(\round($native, 0, PHP_ROUND_HALF_ODD))
        ;
    }

    /*
     * Test toReal.
     */
    public function testToReal()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->when($real = $number->toReal())
            ->then
                ->object($real)
                    ->isInstanceOf(Real::class)
                ->variable($real->toNative())
                    ->isEqualTo((float) $native)
        ;
    }

    /*
     * Test toDecimal.
     */
    public function testToDecimal()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->when($decimal = $number->toDecimal())
            ->then
                ->object($decimal)
                    ->isInstanceOf(Decimal::class)
                ->variable($decimal->toNative())
                    ->isEqualTo($native)
        ;
    }

    /*
     * Test isInfinite.
     */
    public function testIsInfinite()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->then
                ->boolean($number->isInfinite())
                    ->isFalse()
        ;
    }

    /*
     * Test isPositive.
     */
    public function testIsPositive()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->then
                ->boolean($number->isPositive())
                    ->isTrue()
        ;

        $this
            ->given(
                $native = $this->negativeNativeNumber(),
                $number = $this->fromNative($native)
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
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->then
                ->boolean($number->isNegative())
                ->isFalse()
        ;

        $this
            ->given(
                $native = $this->negativeNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->then
                ->boolean($number->isNegative())
                ->isTrue()
        ;
    }

    /*
     * Test isZero.
     */
    public function testIsZero()
    {
        $this
            ->given(
                $native = $this->randomNativeNumber(),
                $number = $this->fromNative($native)
            )
            ->then
                ->boolean($number->isZero())
                ->isFalse()
        ;

        $this
            ->given(
                $number = $this->fromNative(0)
            )
            ->then
                ->boolean($number->isZero())
                ->isTrue()
        ;
    }

    /*
     * Test add.
     */
    public function testAdd()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->add($b->toInteger()))
            ->and($d = $a->addInteger($b->toInteger()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->add($b->toReal()))
            ->and($d = $a->addReal($b->toReal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->add($b->toDecimal()))
            ->and($d = $a->addDecimal($b->toDecimal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;
    }

    /*
     * Test add commutative.
     */
    public function testAddCommutative()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber())->toInteger(),
                $b = $this->fromNative($this->randomNativeNumber())->toReal(),
                $c = $this->fromNative($this->randomNativeNumber())->toDecimal()
            )
            ->then
                ->boolean(
                    $a->add($b)->equals($b->add($a))
                )->isTrue()
                ->boolean(
                    $a->add($c)->equals($c->add($a))
                )->isTrue()
                ->boolean(
                    $b->add($c)->equals($c->add($b))
                )->isTrue()
        ;
    }

    /*
     * Test add associative.
     */
    public function testAddAssociative()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber())->toInteger(),
                $b = $this->fromNative($this->randomNativeNumber())->toReal(),
                $c = $this->fromNative($this->randomNativeNumber())->toDecimal()
            )
            ->then
                ->boolean(
                    $a->add($b)->add($c)->equals($a->add($b->add($c)))
                )->isTrue()
                ->boolean(
                    $a->add($c)->add($b)->equals($a->add($c->add($b)))
                )->isTrue()
                ->boolean(
                    $b->add($a)->add($c)->equals($b->add($a->add($c)))
                )->isTrue()
                ->boolean(
                    $b->add($c)->add($a)->equals($b->add($c->add($a)))
                )->isTrue()
                ->boolean(
                    $c->add($a)->add($b)->equals($c->add($a->add($b)))
                )->isTrue()
                ->boolean(
                    $c->add($b)->add($a)->equals($c->add($b->add($a)))
                )->isTrue()
        ;
    }

    /*
     * Test add zero.
     */
    public function testAddZero()
    {
        $this
            ->given(
                $zero = $this->fromNative(0),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean(
                    $number->add($zero)->equals($number)
                )->isTrue()
                ->boolean(
                    $zero->add($number)->equals($number)
                )->isTrue()
        ;
    }

    /*
     * Test sub.
     */
    public function testSub()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->sub($b->toInteger()))
            ->and($d = $a->subInteger($b->toInteger()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->sub($b->toReal()))
            ->and($d = $a->subReal($b->toReal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->sub($b->toDecimal()))
            ->and($d = $a->subDecimal($b->toDecimal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;
    }

    /*
     * Test sub zero.
     */
    public function testSubZero()
    {
        $this
            ->given(
                $zero = $this->fromNative(0),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean(
                    $number->sub($zero)->equals($number)
                )->isTrue()
                ->boolean(
                    $zero->sub($number)->isNegative()
                )->isTrue()
        ;
    }

    /*
     * Test mult.
     */
    public function testMult()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->mult($b->toInteger()))
            ->and($d = $a->multInteger($b->toInteger()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->mult($b->toReal()))
            ->and($d = $a->multReal($b->toReal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->mult($b->toDecimal()))
            ->and($d = $a->multDecimal($b->toDecimal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;
    }

    /*
     * Test mult commutative.
     */
    public function testMultCommutative()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber())->toInteger(),
                $b = $this->fromNative($this->randomNativeNumber())->toReal(),
                $c = $this->fromNative($this->randomNativeNumber())->toDecimal()
            )
            ->then
                ->boolean(
                    $a->mult($b)->equals($b->mult($a))
                )->isTrue()
                ->boolean(
                    $a->mult($c)->equals($c->mult($a))
                )->isTrue()
                ->boolean(
                    $b->mult($c)->equals($c->mult($b))
                )->isTrue()
        ;
    }

    /*
     * Test mult associative.
     */
    public function testMultAssociative()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber())->toInteger(),
                $b = $this->fromNative($this->randomNativeNumber())->toReal(),
                $c = $this->fromNative($this->randomNativeNumber())->toDecimal()
            )
            ->then
                ->boolean(
                    $a->mult($b)->mult($c)->equals($a->mult($b->mult($c)))
                )->isTrue()
                ->boolean(
                    $a->mult($c)->mult($b)->equals($a->mult($c->mult($b)))
                )->isTrue()
                ->boolean(
                    $b->mult($a)->mult($c)->equals($b->mult($a->mult($c)))
                )->isTrue()
                ->boolean(
                    $b->mult($c)->mult($a)->equals($b->mult($c->mult($a)))
                )->isTrue()
                ->boolean(
                    $c->mult($a)->mult($b)->equals($c->mult($a->mult($b)))
                )->isTrue()
                ->boolean(
                    $c->mult($b)->mult($a)->equals($c->mult($b->mult($a)))
                )->isTrue()
        ;
    }

    /*
     * Test mult associative.
     */
    public function testMultDistributive()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber())->toInteger(),
                $b = $this->fromNative($this->randomNativeNumber())->toReal(),
                $c = $this->fromNative($this->randomNativeNumber())->toDecimal()
            )
            ->then
                ->boolean(
                    $a->mult($b->add($c))->equals($a->mult($b)->add($a->mult($c)))
                )->isTrue()
                ->boolean(
                    $b->mult($a->add($c))->equals($b->mult($a)->add($b->mult($c)))
                )->isTrue()
                ->boolean(
                    $c->mult($a->add($b))->equals($c->mult($a)->add($c->mult($b)))
                )->isTrue()
                ->boolean(
                    $a->mult($b->sub($c))->equals($a->mult($b)->sub($a->mult($c)))
                )->isTrue()
                ->boolean(
                    $b->mult($a->sub($c))->equals($b->mult($a)->sub($b->mult($c)))
                )->isTrue()
                ->boolean(
                    $c->mult($a->sub($b))->equals($c->mult($a)->sub($c->mult($b)))
                )->isTrue()
        ;
    }

    /*
     * Test mult zero.
     */
    public function testMultZero()
    {
        $this
            ->given(
                $zero = $this->fromNative(0),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean(
                    $number->mult($zero)->equals($zero)
                )->isTrue()
                ->boolean(
                    $zero->mult($number)->equals($zero)
                )->isTrue()
        ;
    }

    /*
     * Test mult one.
     */
    public function testMultOne()
    {
        $this
            ->given(
                $one = $this->fromNative(1),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean(
                    $number->mult($one)->equals($number)
                )->isTrue()
                ->boolean(
                    $one->mult($number)->equals($number)
                )->isTrue()
        ;
    }

    /*
     * Test div.
     */
    public function testDiv()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->div($b->toInteger()))
            ->and($d = $a->divInteger($b->toInteger()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->div($b->toReal()))
            ->and($d = $a->divReal($b->toReal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->div($b->toDecimal()))
            ->and($d = $a->divDecimal($b->toDecimal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;
    }

    /*
     * Test div by zero.
     */
    public function testDivByZero()
    {
        $this
            ->given(
                $zero = $this->fromNative(0),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->exception(function () use ($number, $zero) {
                    $number->div($zero);
                })
                ->isInstanceOf(\DomainException::class)
        ;
    }

    /*
     * Test div special cases.
     */
    public function testDivSpecialCases()
    {
        $this
            ->given(
                $zero = $this->fromNative(0),
                $number = $this->fromNative($this->randomNativeNumber())
            )
            ->then
                ->boolean(
                    $zero->div($number)->equals($zero)
                )->isTrue()
        ;
    }

    /*
     * Test pow.
     */
    public function testPow()
    {
        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->pow($b->toInteger()))
            ->and($d = $a->powInteger($b->toInteger()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->pow($b->toReal()))
            ->and($d = $a->powReal($b->toReal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;

        $this
            ->given(
                $a = $this->fromNative($this->randomNativeNumber()),
                $b = $this->fromNative($this->randomNativeNumber())
            )
            ->when($c = $a->pow($b->toDecimal()))
            ->and($d = $a->powDecimal($b->toDecimal()))
            ->then
                ->object($c)
                    ->isEqualTo($d)
        ;
    }

    /*
     * Test compareTo.
     */
    public function testCompareTo()
    {
        $this
            ->given(
                $one = $this->fromNative(1),
                $number = $this->fromNative($this->randomNativeNumber()),
                $greather = $number->add($one),
                $less = $number->sub($one)
            )
            ->then
                ->variable($number->compareTo($number))
                    ->isEqualTo(0)
                ->variable($number->compareTo($greather))
                    ->isEqualTo(-1)
                ->variable($number->compareTo($less))
                    ->isEqualTo(1)
            ->exception(function () use ($number) {
                $number->toInteger()->compareTo(StringLiteral::fromNative('foo'));
            })
            ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
