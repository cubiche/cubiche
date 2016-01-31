<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Specification\Constraint;

use Cubiche\Domain\Collections\Specification\Constraint\Same;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;

/**
 * Same Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SameTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $same = new Same(new This(), new Value(5));
        $this->assertTrue($same->evaluate(5));
        $this->assertFalse($same->evaluate(5.0));
        $this->assertFalse($same->evaluate(4));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new Same(new Value(10), new Value(5)), 'visitSame');
    }
}
