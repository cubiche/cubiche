<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Specification\Quantifier;

use Cubiche\Domain\Collections\Specification\Quantifier\All;
use Cubiche\Domain\Collections\Specification\Selector\SelfSelector;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;

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
        $all = new All(new SelfSelector(), new GreaterThan(new SelfSelector(), new Value(5)));
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
        $this->visitTest(new All(new SelfSelector(), new Value(true)), 'visitAll');
    }
}
