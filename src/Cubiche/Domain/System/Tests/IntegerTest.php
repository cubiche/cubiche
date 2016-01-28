<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\Real;

/**
 * Integer Test.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class IntegerTest extends NumberTestCase
{
    /**
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct(Integer::class, $name, $data, $dataName);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\Tests\NativeValueObjectTestCase::firstNativeValue()
     */
    protected function firstNativeValue()
    {
        return 3;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\Tests\NativeValueObjectTestCase::secondNativeValue()
     */
    protected function secondNativeValue()
    {
        return 5;
    }

    /**
     * @test
     */
    public function constructor()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        Integer::fromNative(10.2);
    }

    /**
     * @test
     */
    public function add()
    {
        parent::add();

        $result = $this->number()->addInteger($this->secondValue());
        $this->assertInstanceOf(Integer::class, $result);
        $this->assertEquals($this->firstNativeValue() + $this->secondNativeValue(), $result->toNative());
        $this->assertTrue($this->number()->addReal($this->realValue())->equals(
            $this->number()->toReal()->addReal($this->realValue())
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

        $result = $this->number()->subInteger($this->secondValue());
        $this->assertInstanceOf(Integer::class, $result);
        $this->assertEquals($this->firstNativeValue() - $this->secondNativeValue(), $result->toNative());
        $this->assertTrue($this->number()->subReal($this->realValue())->equals(
            $this->number()->toReal()->subReal($this->realValue())
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

        $result = $this->number()->multInteger($this->secondValue());
        $this->assertInstanceOf(Integer::class, $result);
        $this->assertEquals($this->firstNativeValue() * $this->secondNativeValue(), $result->toNative());
        $this->assertTrue($this->number()->multReal($this->realValue())->equals(
            $this->number()->toReal()->multReal($this->realValue())
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

        $result = $this->number()->divInteger($this->secondValue());
        $this->assertInstanceOf(Real::class, $result);
        $this->assertEquals($this->firstNativeValue() / $this->secondNativeValue(), $result->toNative());
        $this->assertTrue($this->number()->divReal($this->realValue())->equals(
            $this->number()->toReal()->divReal($this->realValue())
        ));
        $this->assertTrue($this->number()->divDecimal($this->decimalValue())->equals(
            $this->number()->toDecimal()->divDecimal($this->decimalValue())
        ));
        $this->assertTrue($this->number()->divDecimal($this->decimalValue(), 2)->equals(
            $this->number()->toDecimal()->divDecimal($this->decimalValue(), 2)
        ));
    }

    /**
     * @test
     */
    public function pow()
    {
        parent::pow();

        $result = $this->number()->powInteger($this->secondValue());
        $this->assertInstanceOf(Integer::class, $result);
        $this->assertEquals(\pow($this->firstNativeValue(), $this->secondNativeValue()), $result->toNative());
        $this->assertTrue($this->number()->powReal($this->realValue())->equals(
            $this->number()->toReal()->powReal($this->realValue())
        ));
        $this->assertTrue($this->number()->powDecimal($this->decimalValue())->equals(
            $this->number()->toDecimal()->powDecimal($this->decimalValue())
        ));
        $this->assertTrue($this->number()->powDecimal($this->decimalValue(), 2)->equals(
            $this->number()->toDecimal()->powDecimal($this->decimalValue(), 2)
        ));
    }

    /**
     * @test
     */
    public function sqrt()
    {
        $result = $this->number()->sqrt();
        $this->assertInstanceOf(Real::class, $result);
        $this->assertEquals(\sqrt($this->firstNativeValue()), $result->toNative());
    }

    /**
     * @test
     */
    public function inc()
    {
        $this->assertEquals($this->firstNativeValue() + 1, $this->integerValue()->inc()->toNative());
    }

    /**
     * @test
     */
    public function dec()
    {
        $this->assertEquals($this->firstNativeValue() - 1, $this->integerValue()->dec()->toNative());
    }

    /**
     * @test
     */
    public function mod()
    {
        $result = $this->secondValue()->mod($this->integerValue());
        $this->assertInstanceOf(Integer::class, $result);
        $this->assertEquals($this->secondNativeValue() % $this->firstNativeValue(), $result->toNative());
    }

    /**
     * @test
     */
    public function isEven()
    {
        $this->assertTrue(Integer::fromNative(6)->isEven());
        $this->assertFalse(Integer::fromNative(3)->isEven());
    }

    /**
     * @test
     */
    public function isOdd()
    {
        $this->assertFalse(Integer::fromNative(6)->isOdd());
        $this->assertTrue(Integer::fromNative(3)->isOdd());
    }
}
