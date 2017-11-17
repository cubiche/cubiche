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
use Cubiche\Core\Enumerable\Tests\Fixtures\Value;

/**
 * Enumerable Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EnumerableTests extends AbstractEnumerableTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array($this->defaultTestedInstanceIterator());
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTestedInstanceIterator()
    {
        return new \ArrayIterator(array(new Value(1), new Value(2), new Value(3)));
    }

    /**
     * Test from method.
     *
     * @param array|\Traversable $source
     * @param \Iterator          $iterator
     *
     * @dataProvider fromDataProvider
     */
    public function testFrom($source, \Iterator $iterator)
    {
        $this
            ->when($enumerable = Enumerable::from($source))
            ->then()
                ->enumerable($enumerable)
                    ->iteratorAreEqualTo($iterator)
        ;
    }

    /**
     * Test from method.
     */
    public function testFromNotTraversable()
    {
        $this
            ->exception(function () {
                Enumerable::from(new \stdClass());
            })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test emptyEnumerable method.
     */
    public function testEmptyEnumerable()
    {
        $this
            ->when($empty = Enumerable::emptyEnumerable())
                ->enumerable($empty)
                    ->isEmpty()
        ;
    }
    /**
     * Test contains method.
     *
     * @dataProvider containsDataProvider
     */
    public function testContains($value, callable $equalityComparer = null, $expected = true)
    {
        $this->skip('skipped');
    }

    /**
     * @return array
     */
    protected function fromDataProvider()
    {
        return array(
            array($this->newDefaultTestedInstance(), $this->defaultTestedInstanceIterator()),
            array(array(1, 2, 3), new \ArrayIterator(array(1, 2, 3))),
            array(new \ArrayIterator(array(1, 2, 3)), new \ArrayIterator(array(1, 2, 3))),
            array(new \SimpleXMLElement('<foo></foo>'), new \EmptyIterator()),
        );
    }
}
