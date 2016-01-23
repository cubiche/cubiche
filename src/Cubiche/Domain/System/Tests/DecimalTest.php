<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\System\Decimal;

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
        return 6.179;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\System\Tests\RealTestCase::secondNativeValue()
     */
    protected function secondNativeValue()
    {
        return 4.57;
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
}
