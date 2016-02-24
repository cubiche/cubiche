<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Selector;

use Cubiche\Domain\Specification\Selector\Property;
use Cubiche\Domain\Specification\Tests\SpecificationTestCase;

/**
 * Property Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $property = new Property('foo');
        $this->assertTrue($property->evaluate((object) array('foo' => true)));
        $this->assertFalse($property->evaluate((object) array('foo' => false)));
        $this->assertFalse($property->evaluate((object) array('foo' => 'bar')));
    }

    /**
     * @test
     */
    public function testApply()
    {
        $property = new Property('foo');
        $this->assertEquals('bar', $property->apply((object) array('foo' => 'bar')));
    }

    /**
     * @test
     */
    public function testEvaluateNonObject()
    {
        $property = new Property('foo');
        $this->setExpectedException(\RuntimeException::class, 'Trying to get property of non-object');
        $property->evaluate(null);
    }

    /**
     * @test
     */
    public function testApplyNonObject()
    {
        $property = new Property('foo');
        $this->setExpectedException(\RuntimeException::class, 'Trying to get property of non-object');
        $property->apply(null);
    }

    /**
     * @test
     */
    public function testEvaluateUndefinedProperty()
    {
        $property = new Property('foo');
        $this->setExpectedException(\RuntimeException::class, 'Undefined property stdClass::foo');
        $property->evaluate((object) array());
    }

    /**
     * @test
     */
    public function testApplyUndefinedProperty()
    {
        $property = new Property('foo');
        $this->setExpectedException(\RuntimeException::class, 'Undefined property stdClass::foo');
        $property->apply((object) array());
    }

    /**
     * @test
     */
    public function testName()
    {
        $property = new Property('foo');
        $this->assertEquals('foo', $property->name());
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new Property('foo'), 'visitProperty');
    }
}
