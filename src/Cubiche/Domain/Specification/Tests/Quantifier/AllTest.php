<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Quantifier;

use Cubiche\Domain\Specification\Quantifier\All;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\Tests\SpecificationTestCase;
use Cubiche\Domain\Specification\Constraint\GreaterThan;

/**
 * All Quantifier Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class AllTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $all = new All(new This(), new GreaterThan(new This(), new Value(5)));
        $this->assertTrue($all->evaluate(array(6, 7, 8, 9)));
        $this->assertTrue($all->evaluate(array()));
        $this->assertTrue($all->evaluate(6));
        $this->assertFalse($all->evaluate(array(6, 7, 8, 9, 5)));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new All(new This(), new Value(true)), 'visitAll');
    }
}
