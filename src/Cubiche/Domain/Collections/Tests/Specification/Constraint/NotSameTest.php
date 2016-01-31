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

use Cubiche\Domain\Collections\Specification\Constraint\NotSame;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;

/**
 * Not Same Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotSameTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $notsame = new NotSame(new This(), new Value(5));
        $this->assertFalse($notsame->evaluate(5));
        $this->assertTrue($notsame->evaluate(5.0));
        $this->assertTrue($notsame->evaluate(5.0));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new NotSame(new Value(10), new Value(5)), 'visitNotSame');
    }
}
