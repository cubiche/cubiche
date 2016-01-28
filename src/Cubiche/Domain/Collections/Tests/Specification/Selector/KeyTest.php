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

use Cubiche\Domain\Collections\Specification\Selector\Key;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;

/**
 * Key Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class KeyTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $key = new Key('foo');
        $this->assertTrue($key->evaluate(array('foo' => true)));
        $this->assertFalse($key->evaluate(array('foo' => false)));
        $this->assertFalse($key->evaluate(array('foo' => 'bar')));
    }

    /**
     * @test
     */
    public function testApply()
    {
        $key = new Key('foo');
        $this->assertEquals('bar', $key->apply(array('foo' => 'bar')));
        $this->assertEquals(null, $key->apply(null));
        $this->assertEquals(null, $key->apply(array()));
    }

    /**
     * @test
     */
    public function testName()
    {
        $key = new Key('foo');
        $this->assertEquals('foo', $key->name());
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new Key('foo'), 'visitKey');
    }
}
