<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Specification\Selector;

use Cubiche\Domain\Collections\Specification\Selector\Custom;
use Cubiche\Domain\Collections\Tests\Specification\SpecificationTestCase;

/**
 * Custom Selector Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CustomTest extends SpecificationTestCase
{
    /**
     * @test
     */
    public function testEvaluate()
    {
        $custom = new Custom(function ($value) {
            return !$value;
        });

        $this->assertTrue($custom->evaluate(false));
        $this->assertFalse($custom->evaluate(true));
    }

    /**
     * @test
     */
    public function testApply()
    {
        $custom = new Custom(function ($value) {
            return $value + 1;
        });

        $this->assertEquals(2, $custom->apply(1));
    }

    /**
     * @test
     */
    public function testVisit()
    {
        $this->visitTest(new Custom(function () {

        }), 'visitCustom');
    }
}
