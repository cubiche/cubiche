<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\System\Integer;

/**
 * Integer Test.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class IntegerTest extends NumberTestCase
{
    /**
     * @var int
     */
    protected $first;

    /**
     * @var int
     */
    protected $second;

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
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->first = $this->firstValue();
        $this->second = $this->secondValue();
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
}
