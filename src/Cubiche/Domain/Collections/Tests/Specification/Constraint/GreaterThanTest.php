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

use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\Selector\SelfSelector;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;
use Cubiche\Domain\Comparable\ComparableInterface;

/**
 * Greater Than Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class GreaterThanTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $gt = new GreaterThan(new SelfSelector(), new Value(5));
        $this->assertTrue($gt->evaluate(6));
        $this->assertFalse($gt->evaluate(5));
        $this->assertFalse($gt->evaluate(4));
    }

    /**
     * @test
     */
    public function testEvaluateComparable()
    {
        $comparableMock = $this->getMock(ComparableInterface::class);
        $comparableMock
            ->expects($this->exactly(3))
            ->method('compareTo')
            ->with($this->identicalTo(5))
            ->willReturnOnConsecutiveCalls(1, 0, -1);

        $gt = new GreaterThan(new SelfSelector(), new Value(5));
        $this->assertTrue($gt->evaluate($comparableMock));
        $this->assertFalse($gt->evaluate($comparableMock));
        $this->assertFalse($gt->evaluate($comparableMock));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new GreaterThan(new Value(10), new Value(5)), 'visitGreaterThan');
    }
}
