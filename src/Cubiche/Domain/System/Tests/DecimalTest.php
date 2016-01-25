<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\System\Decimal;
use Cubiche\Domain\System\DecimalInfinite;
use Cubiche\Domain\Exception\NotImplementedException;

/**
 * Decimal Test.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DecimalTest extends RealTestCase
{
    /**
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct(Decimal::class, $name, $data, $dataName);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Tests\RealTestCase::firstNativeValue()
     */
    protected function firstNativeValue()
    {
        return '4.179';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Tests\RealTestCase::secondNativeValue()
     */
    protected function secondNativeValue()
    {
        return '7.57';
    }

    /**
     * @test
     */
    public function setDefaultScale()
    {
        Decimal::setDefaultScale(8);
        $this->assertEquals(8, Decimal::defaultScale());
    }

    /**
     * @test
     */
    public function setDefaultScaleInt()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        Decimal::setDefaultScale(8.1);
    }

    /**
     * @test
     */
    public function infiniteFromNative()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        DecimalInfinite::fromNative($this->firstNativeValue());
    }

    /**
     * @test
     */
    public function setDefaultScalePositive()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        Decimal::setDefaultScale(-2);
    }

    /**
     * @test
     */
    public function scaleInt()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->decimalValue()->addDecimal($this->decimalValue(), 8.1);
    }

    /**
     * @test
     */
    public function scalePositive()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->decimalValue()->addDecimal($this->decimalValue(), -2);
    }

    /**
     * @test
     */
    public function add()
    {
        parent::add();

        $result = $this->number()->addDecimal($this->secondValue());
        $this->assertInstanceOf(Decimal::class, $result);
        $this->assertEquals(
            \bcadd($this->firstNativeValue(), $this->secondNativeValue(), Decimal::defaultScale()),
            $result->toNative()
        );
        $this->assertTrue($this->number()->addInteger($this->integerValue())->equals(
            $this->number()->addDecimal($this->integerValue()->toDecimal())
        ));
        $this->assertTrue($this->number()->addReal($this->realValue())->equals(
            $this->number()->addDecimal($this->realValue()->toDecimal())
        ));
        $result = $this->number()->addDecimal($this->secondValue(), 2);
        $this->assertEquals(\bcadd($this->firstNativeValue(), $this->secondNativeValue(), 2), $result->toNative());
    }

    /**
     * @test
     */
    public function addInfinite()
    {
        $this->assertTrue(
            $this->positiveInfinite()->addInteger($this->integerValue())->equals($this->positiveInfinite())
        );
        $this->assertTrue(
            $this->positiveInfinite()->addReal($this->realValue())->equals($this->positiveInfinite())
        );
        $this->assertTrue(
            $this->positiveInfinite()->addDecimal($this->decimalValue())->equals($this->positiveInfinite())
        );

        parent::addInfinite();
    }

    /**
     * @test
     */
    public function sub()
    {
        parent::sub();

        $result = $this->number()->subDecimal($this->secondValue());
        $this->assertInstanceOf(Decimal::class, $result);
        $this->assertEquals(
            \bcsub($this->firstNativeValue(), $this->secondNativeValue(), Decimal::defaultScale()),
            $result->toNative()
        );
        $this->assertTrue($this->number()->subInteger($this->integerValue())->equals(
            $this->number()->subDecimal($this->integerValue()->toDecimal())
        ));
        $this->assertTrue($this->number()->subReal($this->realValue())->equals(
            $this->number()->subDecimal($this->realValue()->toDecimal())
        ));
        $result = $this->number()->subDecimal($this->secondValue(), 2);
        $this->assertEquals(\bcsub($this->firstNativeValue(), $this->secondNativeValue(), 2), $result->toNative());
    }

    /**
     * @test
     */
    public function subInfinite()
    {
        $this->assertTrue(
            $this->positiveInfinite()->subInteger($this->integerValue())->equals($this->positiveInfinite())
        );
        $this->assertTrue(
            $this->positiveInfinite()->subReal($this->realValue())->equals($this->positiveInfinite())
        );
        $this->assertTrue(
            $this->positiveInfinite()->subDecimal($this->decimalValue())->equals($this->positiveInfinite())
        );

        parent::subInfinite();
    }

    /**
     * @test
     */
    public function mult()
    {
        parent::mult();

        $result = $this->number()->multDecimal($this->secondValue());
        $this->assertInstanceOf(Decimal::class, $result);
        $this->assertEquals(
            \bcmul($this->firstNativeValue(), $this->secondNativeValue(), Decimal::defaultScale()),
            $result->toNative()
        );
        $this->assertTrue($this->number()->multInteger($this->integerValue())->equals(
            $this->number()->multDecimal($this->integerValue()->toDecimal())
        ));
        $this->assertTrue($this->number()->multReal($this->realValue())->equals(
            $this->number()->multDecimal($this->realValue()->toDecimal())
        ));
        $result = $this->number()->multDecimal($this->secondValue(), 2);
        $this->assertEquals(\bcmul($this->firstNativeValue(), $this->secondNativeValue(), 2), $result->toNative());
    }

    /**
     * @test
     */
    public function multInfinite()
    {
        $this->assertTrue(
            $this->positiveInfinite()->multInteger($this->integerValue())->equals($this->positiveInfinite())
        );
        $this->assertTrue(
            $this->positiveInfinite()->multReal($this->realValue())->equals($this->positiveInfinite())
        );
        $this->assertTrue(
            $this->positiveInfinite()->multDecimal($this->decimalValue())->equals($this->positiveInfinite())
        );

        parent::multInfinite();
    }

    /**
     * @test
     */
    public function div()
    {
        parent::div();

        $result = $this->number()->divDecimal($this->secondValue());
        $this->assertInstanceOf(Decimal::class, $result);
        $this->assertEquals(
            \bcdiv($this->firstNativeValue(), $this->secondNativeValue(), Decimal::defaultScale()),
            $result->toNative()
        );
        $this->assertTrue($this->number()->divInteger($this->integerValue())->equals(
            $this->number()->divDecimal($this->integerValue()->toDecimal())
        ));
        $this->assertTrue($this->number()->divReal($this->realValue())->equals(
            $this->number()->divDecimal($this->realValue()->toDecimal())
        ));
        $result = $this->number()->divDecimal($this->secondValue(), 2);
        $this->assertEquals(\bcdiv($this->firstNativeValue(), $this->secondNativeValue(), 2), $result->toNative());
    }

    /**
     * @test
     */
    public function divInfinite()
    {
        $this->assertTrue(
            $this->positiveInfinite()->divInteger($this->integerValue())->equals($this->positiveInfinite())
        );
        $this->assertTrue(
            $this->positiveInfinite()->divReal($this->realValue())->equals($this->positiveInfinite())
        );
        $this->assertTrue(
            $this->positiveInfinite()->divDecimal($this->decimalValue())->equals($this->positiveInfinite())
        );

        parent::divInfinite();
    }

    /**
     * @test
     */
    public function pow()
    {
        parent::pow();

        $result = $this->number()->powDecimal($this->secondValue());
        $this->assertInstanceOf(Decimal::class, $result);
        $this->assertEquals(\pow($this->firstNativeValue(), $this->secondNativeValue()), $result->toNative());
        $this->assertEquals(
            $this->number()->powInteger($this->integerValue())->toNative(),
            \bcpow($this->firstNativeValue(), $this->integerValue()->toNative(), Decimal::defaultScale())
        );
        $this->assertTrue($this->number()->powReal($this->realValue())->equals(
            $this->number()->powDecimal($this->realValue()->toDecimal())
        ));
    }

    /**
     * @test
     */
    public function powInfiniteToZero()
    {
        $this->setExpectedException(NotImplementedException::class);
        $this->positiveInfinite()->pow($this->zero());
    }

    /**
     * @test
     */
    public function powInfiniteToInfinite()
    {
        $this->setExpectedException(\DomainException::class);
        $this->negativeInfinite()->pow($this->positiveInfinite());
    }

    /**
     * @test
     */
    public function powInfiniteToReal()
    {
        $this->setExpectedException(NotImplementedException::class);
        $this->negativeInfinite()->powReal($this->realValue());
    }

    /**
     * @test
     */
    public function sqrt()
    {
        $result = $this->number()->sqrt();
        $this->assertInstanceOf(Decimal::class, $result);
        $this->assertEquals(\bcsqrt($this->firstNativeValue(), Decimal::defaultScale()), $result->toNative());

        $result = $this->number()->sqrt(2);
        $this->assertEquals(\bcsqrt($this->firstNativeValue(), 2), $result->toNative());
    }

    /**
     * @test
     */
    public function sqrtInfinite()
    {
        $this->setExpectedException(NotImplementedException::class);
        $this->positiveInfinite()->sqrt();
    }
}
