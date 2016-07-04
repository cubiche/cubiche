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

use Cubiche\Core\Enumerable\EnumerableInterface;

/**
 * Enumerable Interface Test Case class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class EnumerableInterfaceTestCase extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(EnumerableInterface::class)
        ;
    }

    /**
     * Test count method.
     */
    public function testCount()
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->then()
                ->enumerable($enumerable)
                    ->count()
                        ->isEqualTo(\iterator_count($enumerable->getIterator()))
        ;
    }

    /**
     * Test getIterator method.
     *
     * @dataProvider getIteratorDataProvider
     */
    public function testGetIterator(\Iterator $iterator)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->then()
                ->enumerable($enumerable)
                    ->iteratorAreEqualTo($iterator)
        ;
    }

    /**
     * Test where method.
     *
     * @dataProvider whereDataProvider
     */
    public function testWhere(\Iterator $iterator, callable $predicate)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->then()
                ->enumerable($enumerable->where($predicate))
                    ->iteratorAreEqualTo(new \CallbackFilterIterator($iterator, $predicate))
        ;
    }

    /**
     * Test slice method.
     *
     * @dataProvider sliceDataProvider
     */
    public function testSlice(\Iterator $iterator)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->let($count = $enumerable->count())
            ->let($offset = \rand(0, $count - 1))
            ->let($length = \rand(1, $count - $offset))
            ->when($sliced = $enumerable->slice($offset, $length))
            ->then()
                ->enumerable($sliced)
                    ->iteratorAreEqualTo(new \LimitIterator($iterator, $offset, $length))
        ;

        $this
            ->when($sliced = $enumerable->slice($offset, 0))
            ->then()
                ->enumerable($sliced)
                    ->isEmpty()
        ;

        $this
            ->when($sliced = $enumerable->slice($offset))
            ->then()
                ->enumerable($sliced)
                    ->iteratorAreEqualTo(new \LimitIterator($iterator, $offset))
        ;
    }

    /**
     * Test sorted method.
     *
     * @dataProvider sortedDataProvider
     */
    public function testSorted(\Iterator $iterator, callable $comparator)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->when($sorted = $enumerable->sorted($comparator))
            ->then()
                ->enumerable($sorted)
                    ->iteratorAreEqualTo($this->sortIterator($iterator, $comparator))
        ;
    }

    /**
     * @return array
     */
    protected function getIteratorDataProvider()
    {
        return array(
            array($this->defaultTestedInstanceIterator()),
        );
    }

    /**
     * @return \Iterator
     */
    abstract protected function defaultTestedInstanceIterator();

    /**
     * @return array
     */
    protected function whereDataProvider()
    {
        return array(
            array($this->defaultTestedInstanceIterator(), $this->defaultWherePredicate()),
        );
    }

    /**
     * @return callable
     */
    protected function defaultWherePredicate()
    {
        return function ($value) {
            return $value >= 3 && $value <= 5;
        };
    }

    /**
     * @return array
     */
    protected function sliceDataProvider()
    {
        return $this->getIteratorDataProvider();
    }

    /**
     * @return array
     */
    protected function sortedDataProvider()
    {
        return array(
            array($this->defaultTestedInstanceIterator(), $this->defaultSortedCompartor()),
        );
    }

    /**
     * @return callable
     */
    protected function defaultSortedCompartor()
    {
        return function ($a, $b) {
            return $a - $b;
        };
    }

    /**
     * @param \Iterator $iterator
     * @param callable  $comparator
     *
     * @return \ArrayIterator
     */
    protected function sortIterator(\Iterator $iterator, callable $comparator)
    {
        $array = \iterator_to_array($iterator, true);
        \uasort($array, $comparator);

        return new \ArrayIterator($array);
    }
}
