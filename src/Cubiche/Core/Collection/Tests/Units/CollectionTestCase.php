<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\Tests\Units;

use Cubiche\Core\Collection\CollectionInterface;
use Cubiche\Core\Comparable\Comparator;

/**
 * Collection Test Case class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class CollectionTestCase extends TestCase
{
    /**
     * @param int $size
     *
     * @return CollectionInterface
     */
    abstract protected function randomCollection($size = null);

    /**
     * @param int $size
     *
     * @return mixed[]
     */
    protected function randomValues($size = null)
    {
        $items = array();
        if ($size === null) {
            $size = \rand(10, 20);
        }
        foreach (\range(0, $size - 1) as $value) {
            $items[$value] = $this->randomValue();
        }

        return $items;
    }

    /**
     * @return CollectionInterface
     */
    abstract protected function emptyCollection();

    /**
     * @return mixed
     */
    abstract protected function randomValue();

    /**
     * @return mixed
     */
    abstract protected function uniqueValue();

    /**
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    protected function comparator()
    {
        return new Comparator();
    }

    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(CollectionInterface::class)
        ;
    }

    /**
     * Test clear.
     */
    public function testClear()
    {
        $this
            ->given($randomCollection = $this->randomCollection())
            ->then()
                ->collection($randomCollection)
                    ->isNotEmpty()
            ->and()
            ->when($randomCollection->clear())
            ->then()
                ->collection($randomCollection)
                    ->isEmpty()
        ;
    }

    /**
     * Test count.
     */
    public function testCount()
    {
        $this
            ->given($collection = $this->randomCollection(5))
            ->then()
                ->collection($collection)
                    ->size()
                        ->isEqualTo(5)
        ;
    }

    /**
     * Test getIterator.
     */
    public function testGetIterator()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->then
                ->object($collection->getIterator())
                    ->isInstanceOf(\Traversable::class)
        ;
    }

    /**
     * Test toArray.
     */
    public function testToArray()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->when($array = $collection->toArray())
                ->array($array)
                    ->isEqualTo(\iterator_to_array($collection->getIterator()));
    }

    /**
     * Test sorted.
     */
    public function testSorted()
    {
        $this
            ->given(
                $comparator = $this->comparator(),
                $reverseComparator = $comparator->reverse(),
                $collection = $this->randomCollection()
            )
            ->when($sortedCollection = $collection->sorted($comparator))
            ->then()
                ->collection($sortedCollection)
                    ->isSortedUsing($comparator)
                ->collection($sortedCollection)
                    ->isNotSortedUsing($reverseComparator)
        ;
    }
}
