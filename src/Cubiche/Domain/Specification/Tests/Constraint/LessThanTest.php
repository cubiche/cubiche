<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Constraint;

use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\Tests\SpecificationTestCase;
use Cubiche\Domain\Comparable\ComparableInterface;

/**
 * Less Than Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LessThanTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $lt = new LessThan(new This(), new Value(5));
        $this->assertFalse($lt->evaluate(6));
        $this->assertFalse($lt->evaluate(5));
        $this->assertTrue($lt->evaluate(4));
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

        $lt = new LessThan(new This(), new Value(5));
        $this->assertFalse($lt->evaluate($comparableMock));
        $this->assertFalse($lt->evaluate($comparableMock));
        $this->assertTrue($lt->evaluate($comparableMock));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new LessThan(new Value(10), new Value(5)), 'visitLessThan');
    }
}
