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

use Cubiche\Domain\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\Tests\SpecificationTestCase;
use Cubiche\Domain\Comparable\ComparableInterface;

/**
 * Less Than Equal Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LessThanEqualTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $lte = new LessThanEqual(new This(), new Value(5));
        $this->assertFalse($lte->evaluate(6));
        $this->assertTrue($lte->evaluate(5));
        $this->assertTrue($lte->evaluate(4));
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

        $lte = new LessThanEqual(new This(), new Value(5));
        $this->assertFalse($lte->evaluate($comparableMock));
        $this->assertTrue($lte->evaluate($comparableMock));
        $this->assertTrue($lte->evaluate($comparableMock));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new LessThanEqual(new Value(10), new Value(5)), 'visitLessThanEqual');
    }
}
