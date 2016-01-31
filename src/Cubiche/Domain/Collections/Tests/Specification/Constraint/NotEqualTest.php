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

use Cubiche\Domain\Collections\Specification\Constraint\NotEqual;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;
use Cubiche\Domain\Equatable\EquatableInterface;

/**
 * Not Equal Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotEqualTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $neq = new NotEqual(new This(), new Value(5));
        $this->assertFalse($neq->evaluate(5));
        $this->assertFalse($neq->evaluate(5.0));
        $this->assertTrue($neq->evaluate(4));
    }

    /**
     * @test
     */
    public function testEvaluateComparable()
    {
        $comparableMock = $this->getMock(EquatableInterface::class);
        $comparableMock
            ->expects($this->exactly(2))
            ->method('equals')
            ->with($this->identicalTo(5))
            ->willReturnOnConsecutiveCalls(true, false);

        $neq = new NotEqual(new This(), new Value(5));
        $this->assertFalse($neq->evaluate($comparableMock));
        $this->assertTrue($neq->evaluate($comparableMock));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new NotEqual(new Value(10), new Value(5)), 'visitNotEqual');
    }
}
