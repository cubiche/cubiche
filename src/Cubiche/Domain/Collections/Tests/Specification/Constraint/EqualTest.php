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

use Cubiche\Domain\Collections\Specification\Constraint\Equal;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;
use Cubiche\Domain\Equatable\EquatableInterface;

/**
 * Equal Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EqualTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $eq = new Equal(new This(), new Value(5));
        $this->assertTrue($eq->evaluate(5));
        $this->assertTrue($eq->evaluate(5.0));
        $this->assertFalse($eq->evaluate(4));
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

        $eq = new Equal(new This(), new Value(5));
        $this->assertTrue($eq->evaluate($comparableMock));
        $this->assertFalse($eq->evaluate($comparableMock));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new Equal(new Value(10), new Value(5)), 'visitEqual');
    }
}
