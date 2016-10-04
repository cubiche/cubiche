<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable\Tests\Units;

use Cubiche\Core\Enumerable\Enumerable;
use Cubiche\Core\Enumerable\EnumerableDecorator;

/**
 * Enumerable Decorator Test Case class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class EnumerableDecoratorTestCase extends AbstractEnumerableTestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->isSubClassOf(EnumerableDecorator::class)
        ;
    }

    /**
     * Test enumerable method.
     */
    public function testEnumerable()
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableDecorator $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->then()
                ->enumerable($enumerable->enumerable())
        ;
    }

    /**
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    protected function defaultTestedInstanceEnumerableTarget()
    {
        return Enumerable::from($this->defaultValues());
    }
}
