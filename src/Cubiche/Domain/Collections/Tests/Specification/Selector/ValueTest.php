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

use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;

/**
 * Value Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ValueTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluateNumeric()
    {
        $value = new Value(5);
        $this->assertFalse($value->evaluate(null));

        $value = new Value(0);
        $this->assertFalse($value->evaluate(null));
    }

    /**
     * @test
     */
    public function testEvaluateBoolean()
    {
        $value = new Value(true);
        $this->assertTrue($value->evaluate(null));

        $value = new Value(false);
        $this->assertFalse($value->evaluate(null));
    }

    /**
     * @test
     */
    public function testEvaluateNull()
    {
        $value = new Value(null);
        $this->assertFalse($value->evaluate(null));
    }

    /**
     * @test
     */
    public function testValueAndApply()
    {
        $value = new Value(5);
        $this->assertEquals(5, $value->value());
        $this->assertEquals($value->value(), $value->apply(null));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new Value(5), 'visitValue');
    }
}
