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

use Cubiche\Domain\Specification\Tests\SpecificationTestCase;
use Cubiche\Domain\Specification\Selector\This;

/**
 * This Selector Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ThisTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $self = new This();
        $this->assertTrue($self->evaluate(true));
        $this->assertFalse($self->evaluate(false));
        $this->assertFalse($self->evaluate($this));
    }

    /**
     * @test
     */
    public function testApply()
    {
        $self = new This();
        $this->assertTrue($self->apply(true));
        $this->assertFalse($self->apply(false));
        $this->assertEquals($this, $self->apply($this));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new This(), 'visitThis');
    }
}
