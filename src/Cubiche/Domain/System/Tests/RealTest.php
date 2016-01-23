<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\System\Real;

/**
 * Real Test.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class RealTest extends RealTestCase
{
    /**
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct(Real::class, $name, $data, $dataName);
    }

    /**
     * @test
     */
    public function add()
    {
        parent::add();

        $result = $this->number()->addReal($this->secondValue());
        $this->assertInstanceOf(Real::class, $result);
        $this->assertEquals($this->firstNativeValue() + $this->secondNativeValue(), $result->toNative());
        $this->assertTrue($this->number()->addInteger($this->integerValue())->equals(
            $this->number()->addReal($this->integerValue()->toReal())
        ));
        $this->assertTrue($this->number()->addDecimal($this->decimalValue())->equals(
            $this->number()->toDecimal()->addDecimal($this->decimalValue())
        ));
        $this->assertTrue($this->number()->addDecimal($this->decimalValue(), 2)->equals(
            $this->number()->toDecimal()->addDecimal($this->decimalValue(), 2)
        ));
    }

    /**
     * @test
     */
    public function sub()
    {
        parent::sub();

        $result = $this->number()->subReal($this->secondValue());
        $this->assertInstanceOf(Real::class, $result);
        $this->assertEquals($this->firstNativeValue() - $this->secondNativeValue(), $result->toNative());
        $this->assertTrue($this->number()->subInteger($this->integerValue())->equals(
            $this->number()->subReal($this->integerValue()->toReal())
        ));
        $this->assertTrue($this->number()->subDecimal($this->decimalValue())->equals(
            $this->number()->toDecimal()->subDecimal($this->decimalValue())
        ));
        $this->assertTrue($this->number()->subDecimal($this->decimalValue(), 2)->equals(
            $this->number()->toDecimal()->subDecimal($this->decimalValue(), 2)
        ));
    }

    /**
     * @test
     */
    public function mult()
    {
        parent::mult();

        $result = $this->number()->multReal($this->secondValue());
        $this->assertInstanceOf(Real::class, $result);
        $this->assertEquals($this->firstNativeValue() * $this->secondNativeValue(), $result->toNative());
        $this->assertTrue($this->number()->multInteger($this->integerValue())->equals(
            $this->number()->multReal($this->integerValue()->toReal())
        ));
        $this->assertTrue($this->number()->multDecimal($this->decimalValue())->equals(
            $this->number()->toDecimal()->multDecimal($this->decimalValue())
        ));
        $this->assertTrue($this->number()->multDecimal($this->decimalValue(), 2)->equals(
            $this->number()->toDecimal()->multDecimal($this->decimalValue(), 2)
        ));
    }

    /**
     * @test
     */
    public function div()
    {
        parent::div();

        $result = $this->number()->divReal($this->secondValue());
        $this->assertInstanceOf(Real::class, $result);
        $this->assertEquals($this->firstNativeValue() / $this->secondNativeValue(), $result->toNative());
        $this->assertTrue($this->number()->divInteger($this->integerValue())->equals(
            $this->number()->divReal($this->integerValue()->toReal())
        ));
        $this->assertTrue($this->number()->divDecimal($this->decimalValue())->equals(
            $this->number()->toDecimal()->divDecimal($this->decimalValue())
        ));
        $this->assertTrue($this->number()->divDecimal($this->decimalValue(), 2)->equals(
            $this->number()->toDecimal()->divDecimal($this->decimalValue(), 2)
        ));
    }
}
