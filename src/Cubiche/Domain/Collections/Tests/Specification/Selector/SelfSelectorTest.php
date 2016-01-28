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

use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;
use Cubiche\Domain\Collections\Specification\Selector\SelfSelector;

/**
 * Self Selector Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelfSelectorTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $self = new SelfSelector();
        $this->assertTrue($self->evaluate(true));
        $this->assertFalse($self->evaluate(false));
        $this->assertFalse($self->evaluate($this));
    }

    /**
     * @test
     */
    public function testApply()
    {
        $self = new SelfSelector();
        $this->assertTrue($self->apply(true));
        $this->assertFalse($self->apply(false));
        $this->assertEquals($this, $self->apply($this));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new SelfSelector(), 'visitSelf');
    }
}
