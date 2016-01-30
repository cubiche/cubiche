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

use Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;
use Cubiche\Domain\Comparable\ComparableInterface;

/**
 * Greater Than Equal Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class GreaterThanEqualTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $gte = new GreaterThanEqual(new This(), new Value(5));
        $this->assertTrue($gte->evaluate(6));
        $this->assertTrue($gte->evaluate(5));
        $this->assertFalse($gte->evaluate(4));
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

        $gte = new GreaterThanEqual(new This(), new Value(5));
        $this->assertTrue($gte->evaluate($comparableMock));
        $this->assertTrue($gte->evaluate($comparableMock));
        $this->assertFalse($gte->evaluate($comparableMock));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new GreaterThanEqual(new Value(10), new Value(5)), 'visitGreaterThanEqual');
    }
}
