<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\System\Number;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\RoundingMode;
use Cubiche\Domain\System\Real;
use Cubiche\Domain\System\Decimal;
use Cubiche\Domain\Core\Tests\NativeValueObjectTestCase;

/**
 * Number Test Case.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class NumberTestCase extends NativeValueObjectTestCase
{
    /**
     * @param Number       $number
     * @param mixed        $result
     * @param RoundingMode $roundingMode
     */
    protected function toIntegerTest(Number $number, $result, RoundingMode $roundingMode = null)
    {
        $integer = $number->toInteger($roundingMode);
        $this->assertInstanceOf(Integer::class, $integer);
        $this->assertEquals($integer->toNative(), $result);
    }

    /**
     * @return Number
     */
    protected function negativeValue()
    {
        return $this->fromNativeValue(-1 * $this->firstNativeValue());
    }

    /**
     * @return Number
     */
    protected function zero()
    {
        return $this->fromNativeValue(0);
    }

    /**
     * @return Number
     */
    protected function one()
    {
        return $this->fromNativeValue(1);
    }

    /**
     * @return Number
     */
    protected function number()
    {
        return $this->firstValue();
    }

    /**
     * @return int
     */
    protected function integerValue()
    {
        return Integer::fromNative(\round($this->firstNativeValue(), 0, PHP_ROUND_HALF_UP));
    }

    /**
     * @return float
     */
    protected function realValue()
    {
        return Real::fromNative($this->firstNativeValue());
    }

    /**
     * @return float
     */
    protected function decimalValue()
    {
        return Decimal::fromNative($this->firstNativeValue());
    }

    /**
     * @test
     */
    public function toString()
    {
        $this->assertEquals(\strval($this->firstNativeValue()), $this->number()->__toString());
    }

    /**
     * @test
     */
    public function toInteger()
    {
        $this->toIntegerTest(
            $this->number(),
            \round($this->firstNativeValue(), 0, PHP_ROUND_HALF_UP)
        );
        $this->toIntegerTest(
            $this->number(),
            \round($this->firstNativeValue(), 0, PHP_ROUND_HALF_UP),
            RoundingMode::HALF_UP()
        );
        $this->toIntegerTest(
            $this->number(),
            \round($this->firstNativeValue(), 0, PHP_ROUND_HALF_DOWN),
            RoundingMode::HALF_DOWN()
        );
        $this->toIntegerTest(
            $this->number(),
            \round($this->firstNativeValue(), 0, PHP_ROUND_HALF_EVEN),
            RoundingMode::HALF_EVEN()
        );
        $this->toIntegerTest(
            $this->number(),
            \round($this->firstNativeValue(), 0, PHP_ROUND_HALF_ODD),
            RoundingMode::HALF_ODD()
        );
    }

    /**
     * @test
     */
    public function toReal()
    {
        $number = $this->number();
        $real = $number->toReal();
        $this->assertInstanceOf(Real::class, $real);
        $this->assertEquals($real->toNative(), (float) $number->toNative());
    }

    /**
     * @test
     */
    public function toDecimal()
    {
        $number = $this->number();
        $decimal = $number->toDecimal();
        $this->assertInstanceOf(Decimal::class, $decimal);
        $this->assertEquals($decimal->toNative(), $number->toNative());
    }

    /**
     * @test
     */
    public function isInfinite()
    {
        $this->assertFalse($this->number()->isInfinite());
    }

    /**
     * @test
     */
    public function isPositive()
    {
        $this->assertTrue($this->number()->isPositive());
        $this->assertFalse($this->negativeValue()->isPositive());
    }

    /**
     * @test
     */
    public function isNegative()
    {
        $this->assertFalse($this->number()->isNegative());
        $this->assertTrue($this->negativeValue()->isNegative());
    }

    /**
     * @test
     */
    public function isZero()
    {
        $this->assertFalse($this->number()->isZero());
        $this->assertTrue($this->zero()->isZero());
    }

    /**
     * @test
     */
    public function add()
    {
        $result1 = $this->number()->add($this->integerValue());
        $result2 = $this->number()->addInteger($this->integerValue());

        $this->assertTrue($result1->equals($result2));

        $result1 = $this->number()->add($this->realValue());
        $result2 = $this->number()->addReal($this->realValue());

        $this->assertTrue($result1->equals($result2));

        $result1 = $this->number()->add($this->decimalValue());
        $result2 = $this->number()->addDecimal($this->decimalValue());

        $this->assertTrue($result1->equals($result2));
    }

    /**
     * @test
     */
    public function addProperties()
    {
        $a = $this->integerValue();
        $b = $this->realValue();
        $c = $this->decimalValue();

        //Commutative property
        $this->assertTrue($a->add($b)->equals($b->add($a)));
        $this->assertTrue($a->add($c)->equals($c->add($a)));
        $this->assertTrue($b->add($c)->equals($c->add($b)));

        //Associative property
        $this->assertTrue($a->add($b)->add($c)->equals($a->add($b->add($c))));
        $this->assertTrue($a->add($c)->add($b)->equals($a->add($c->add($b))));
        $this->assertTrue($b->add($a)->add($c)->equals($b->add($a->add($c))));
        $this->assertTrue($b->add($c)->add($a)->equals($b->add($c->add($a))));
        $this->assertTrue($c->add($a)->add($b)->equals($c->add($a->add($b))));
        $this->assertTrue($c->add($b)->add($a)->equals($c->add($b->add($a))));
    }

    /**
     * @test
     */
    public function addZero()
    {
        $this->assertTrue($this->number()->add($this->zero())->equals($this->number()));
        $this->assertTrue($this->zero()->add($this->number())->equals($this->number()));
    }

    /**
     * @test
     */
    public function sub()
    {
        $result1 = $this->number()->sub($this->integerValue());
        $result2 = $this->number()->subInteger($this->integerValue());

        $this->assertTrue($result1->equals($result2));

        $result1 = $this->number()->sub($this->realValue());
        $result2 = $this->number()->subReal($this->realValue());

        $this->assertTrue($result1->equals($result2));

        $result1 = $this->number()->sub($this->decimalValue());
        $result2 = $this->number()->subDecimal($this->decimalValue());

        $this->assertTrue($result1->equals($result2));
    }

    /**
     * @test
     */
    public function subZero()
    {
        $this->assertTrue($this->number()->sub($this->zero())->equals($this->number()));
    }

    /**
     * @test
     */
    public function mult()
    {
        $result1 = $this->number()->mult($this->integerValue());
        $result2 = $this->number()->multInteger($this->integerValue());

        $this->assertTrue($result1->equals($result2));

        $result1 = $this->number()->mult($this->realValue());
        $result2 = $this->number()->multReal($this->realValue());

        $this->assertTrue($result1->equals($result2));

        $result1 = $this->number()->mult($this->decimalValue());
        $result2 = $this->number()->multDecimal($this->decimalValue());

        $this->assertTrue($result1->equals($result2));
    }

    /**
     * @test
     */
    public function multProperties()
    {
        $a = $this->integerValue();
        $b = $this->realValue();
        $c = $this->decimalValue();

        //Commutative property
        $this->assertTrue($a->mult($b)->equals($b->mult($a)));
        $this->assertTrue($a->mult($c)->equals($c->mult($a)));
        $this->assertTrue($b->mult($c)->equals($c->mult($b)));

        //Associative property
        $this->assertTrue($a->mult($b)->mult($c)->equals($a->mult($b->mult($c))));
        $this->assertTrue($a->mult($c)->mult($b)->equals($a->mult($c->mult($b))));
        $this->assertTrue($b->mult($a)->mult($c)->equals($b->mult($a->mult($c))));
        $this->assertTrue($b->mult($c)->mult($a)->equals($b->mult($c->mult($a))));
        $this->assertTrue($c->mult($a)->mult($b)->equals($c->mult($a->mult($b))));
        $this->assertTrue($c->mult($b)->mult($a)->equals($c->mult($b->mult($a))));

        //Distributive property
        $this->assertTrue($a->mult($b->add($c))->equals($a->mult($b)->add($a->mult($c))));
        $this->assertTrue($b->mult($a->add($c))->equals($b->mult($a)->add($b->mult($c))));
        $this->assertTrue($c->mult($a->add($b))->equals($c->mult($a)->add($c->mult($b))));
        $this->assertTrue($a->mult($b->sub($c))->equals($a->mult($b)->sub($a->mult($c))));
        $this->assertTrue($b->mult($a->sub($c))->equals($b->mult($a)->sub($b->mult($c))));
        $this->assertTrue($c->mult($a->sub($b))->equals($c->mult($a)->sub($c->mult($b))));
    }

    /**
     * @test
     */
    public function multOneZero()
    {
        $this->assertTrue($this->number()->mult($this->zero())->equals($this->zero()));
        $this->assertTrue($this->zero()->mult($this->number())->equals($this->zero()));
        $this->assertTrue($this->number()->mult($this->one())->equals($this->number()));
        $this->assertTrue($this->one()->mult($this->number())->equals($this->number()));
    }

    /**
     * @test
     */
    public function div()
    {
        $result1 = $this->number()->div($this->integerValue());
        $result2 = $this->number()->divInteger($this->integerValue());

        $this->assertTrue($result1->equals($result2));

        $result1 = $this->number()->div($this->realValue());
        $result2 = $this->number()->divReal($this->realValue());

        $this->assertTrue($result1->equals($result2));

        $result1 = $this->number()->div($this->decimalValue());
        $result2 = $this->number()->divDecimal($this->decimalValue());

        $this->assertTrue($result1->equals($result2));
    }

    /**
     * @test
     */
    public function divByZero()
    {
        $this->setExpectedException(\DomainException::class, 'Division by zero is not allowed.');
        $this->number()->div($this->zero());
    }

    /**
     * @test
     */
    public function divSpecialCases()
    {
        $this->assertTrue($this->zero()->div($this->number())->isZero());
    }
}
