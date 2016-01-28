<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Specification\Selector;

use Cubiche\Domain\Collections\Specification\Selector\Method;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;

/**
 * Method Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class MethodTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $method = new Method('methodTrue');
        $this->assertTrue($method->evaluate($this));

        $method = new Method('methodFalse');
        $this->assertFalse($method->evaluate($this));
    }

    /**
     * @test
     */
    public function testApply()
    {
        $method = new Method('name');
        $this->assertEquals('name', $method->apply($method));
    }

    /**
     * @test
     */
    public function testEvaluateNonObject()
    {
        $this->setExpectedException(\RuntimeException::class, 'Trying to call method of non-object');
        $method = new Method('foo');
        $method->evaluate(null);
    }

    /**
     * @test
     */
    public function testApplyNonObject()
    {
        $this->setExpectedException(\RuntimeException::class, 'Trying to call method of non-object');
        $method = new Method('foo');
        $method->apply(null);
    }

    /**
     * @test
     */
    public function testEvaluateUndefinedMethod()
    {
        $this->setExpectedException(\RuntimeException::class, 'Undefined method '.\get_class($this).'::foo');
        $method = new Method('foo');
        $method->evaluate($this);
    }

    /**
     * @test
     */
    public function testApplyUndefinedMethod()
    {
        $this->setExpectedException(\RuntimeException::class, 'Undefined method '.\get_class($this).'::foo');
        $method = new Method('foo');
        $method->apply($this);
    }

    /**
     * @test
     */
    public function testEvaluateNonPublicMethod()
    {
        $this->setExpectedException(
            \RuntimeException::class,
            'Trying to call non-public method '.\get_class($this).'::privateMethod'
        );

        $method = new Method('privateMethod');
        $method->evaluate($this);
    }

    /**
     * @test
     */
    public function testApplyNonPublicMethod()
    {
        $this->setExpectedException(
            \RuntimeException::class,
            'Trying to call non-public method '.\get_class($this).'::privateMethod'
        );

        $method = new Method('privateMethod');
        $method->apply($this);
    }

    /**
     * @test
     */
    public function testName()
    {
        $method = new Method('foo');
        $this->assertEquals('foo', $method->name());
    }

    /**
     * @test
     */
    public function testArgs()
    {
        $method = new Method('foo');
        $this->assertEmpty($method->args());
    }

    /**
     * @test
     */
    public function testWith()
    {
        $method = new Method('foo');
        $result = $method->with(1, 2, 3);

        $this->assertEquals($method, $result);
        $this->assertEquals(array(1, 2, 3), $method->args());
    }

    /**
     * @test
     */
    public function testEvaluateWith()
    {
        $method = new Method('methodWithArgs');
        $this->assertFalse($method->with(1, 2)->evaluate($this));
    }

    /**
     * @test
     */
    public function testApplyWith()
    {
        $method = new Method('methodWithArgs');
        $this->assertEquals('3', $method->with(1, 2)->apply($this));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new Method('name'), 'visitMethod');
    }

    /**
     * @return bool
     */
    public function methodTrue()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function methodFalse()
    {
        return false;
    }

    /**
     * @param number $arg1
     * @param number $arg2
     *
     * @return number
     */
    public function methodWithArgs($arg1, $arg2)
    {
        return $arg1 + $arg2;
    }

    protected function privateMethod()
    {
    }
}
