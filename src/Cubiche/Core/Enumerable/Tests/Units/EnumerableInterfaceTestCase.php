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

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Enumerable\Enumerable;
use Cubiche\Core\Enumerable\EnumerableInterface;
use Cubiche\Core\Enumerable\Tests\Fixtures\Value;

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
     * Test skip method.
     *
     * @dataProvider sliceDataProvider
     */
    public function testSkip(\Iterator $iterator)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->let($count = $enumerable->count())
            ->let($skip = \rand(0, $count - 1))
            ->when($sliced = $enumerable->skip($skip))
            ->then()
                ->enumerable($sliced)
                    ->iteratorAreEqualTo($enumerable->slice($skip)->getIterator())
        ;
    }

    /**
     * Test limit method.
     *
     * @dataProvider sliceDataProvider
     */
    public function testLimit(\Iterator $iterator)
    {
        $this
        /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->let($count = $enumerable->count())
            ->let($length = \rand(0, $count))
            ->when($sliced = $enumerable->limit($length))
                ->then()
                    ->enumerable($sliced)
                        ->iteratorAreEqualTo($enumerable->slice(0, $length)->getIterator())
        ;
    }

    /**
     * Test sorted method.
     *
     * @dataProvider sortedDataProvider
     */
    public function testSorted(\Iterator $iterator, callable $comparator = null)
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
     * Test all method.
     *
     * @dataProvider quantifierDataProvider
     */
    public function testAll(EnumerableInterface $enumerable, callable $predicate, $count, $length)
    {
        $this
            ->given($enumerable, $predicate, $count, $length)
            ->when($result = $enumerable->all($predicate))
            ->then()
                ->boolean($result)
                    ->isEqualTo($count === $length)
        ;
    }

    /**
     * Test any method.
     *
     * @dataProvider quantifierDataProvider
     */
    public function testAny(EnumerableInterface $enumerable, callable $predicate, $count, $length)
    {
        $this
            ->given($enumerable, $predicate, $count, $length)
            ->when($result = $enumerable->any($predicate))
                ->boolean($result)
                    ->isEqualTo($count > 0)
        ;
    }

    /**
     * Test atLeast method.
     *
     * @dataProvider quantifierDataProvider
     */
    public function testAtLeast(EnumerableInterface $enumerable, callable $predicate, $count, $length)
    {
        $this
            ->given($enumerable, $predicate, $count, $length)
            ->let($x = \rand(0, $length))
            ->when($result = $enumerable->atLeast($x, $predicate))
            ->then()
                ->boolean($result)
                    ->isEqualTo($x <= $count)
        ;
    }

    /**
     * Test contains method.
     *
     * @dataProvider containsDataProvider
     */
    public function testContains($value, callable $equalityComparer = null, $expected = true)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->when($result = $enumerable->contains($value, $equalityComparer))
            ->then()
                ->boolean($result)
                    ->isEqualTo($expected)
        ;
    }

    /**
     * Test distinct method.
     */
    public function testDistinct()
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->when($result = $enumerable->distinct())
            ->then()
                ->enumerable($result)
                    ->areDistinct()
        ;

        $this
            ->given($enumerable)
            ->when($result = $enumerable->distinct($this->alwaysFalseCallable()))
            ->then()
                ->enumerable($result)
                    ->iteratorAreEqualTo($enumerable->getIterator())
        ;
    }

    /**
     * Test except method.
     *
     * @dataProvider setOperationsDataProvider
     */
    public function testExcept($except)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->when($result = $enumerable->except($except))
            ->then()
                ->enumerable($result)
                    ->areDistinct()
        ;

        foreach ($result as $value) {
            $this
                ->boolean(Enumerable::from($except)->contains($value))
                    ->isFalse();
        }
    }

    /**
     * Test intersect method.
     *
     * @dataProvider setOperationsDataProvider
     */
    public function testIntersect($other)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            ->when($intersection = $enumerable->intersect($other))
            ->then()
                ->enumerable($intersection)
                    ->areDistinct()
                ->enumerable($enumerable)
                    ->containsAll($intersection)
                ->enumerable(Enumerable::from($other))
                    ->containsAll($intersection)
        ;
    }

    /**
     * Test union method.
     *
     * @dataProvider setOperationsDataProvider
     */
    public function testUnion($other)
    {
        $this
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
            ->given($enumerable = $this->newDefaultTestedInstance())
            /* @var \Cubiche\Core\Enumerable\EnumerableInterface $union */
            ->when($union = $enumerable->union($other))
            ->then()
                ->enumerable($union)
                    ->areDistinct()
                    ->containsAll($enumerable)
                    ->containsAll($other)
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
        return function (Value $value) {
            return $value->value() >= 3 && $value->value() <= 5;
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
            array($this->defaultTestedInstanceIterator()),
            array($this->defaultTestedInstanceIterator(), $this->defaultSortedComparator()),
        );
    }

    /**
     * @return array
     */
    protected function quantifierDataProvider()
    {
        /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
        $enumerable = $this->newDefaultTestedInstance();
        $length = $enumerable->count();
        $data = array();
        for ($i = 0; $i <= $length; ++$i) {
            $data[] = array(
                $this->newDefaultTestedInstance(),
                $this->atLeastPredicate($i, $length),
                $i,
                $length,
            );
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function atLeastPredicate($count, $length)
    {
        return function ($x) use (&$count, &$length) {
            $result = false;
            if ($count > 0 && ($length <= $count || \rand(0, 1) === 0)) {
                --$count;
                $result = true;
            }
            --$length;

            return $result;
        };
    }

    /**
     * @return array
     */
    protected function containsDataProvider()
    {
        /* @var \Cubiche\Core\Enumerable\EnumerableInterface $enumerable */
        $enumerable = $this->newDefaultTestedInstance();
        $array = $enumerable->toArray();
        $value = $array[\rand(0, \count($array) - 1)];

        return array(
            array($value, null, true),
            array($value, $this->alwaysFalseCallable(), false),
            array(new Value(\uniqid()), null, false),
            array(new Value(\uniqid()), $this->alwaysTrueCallable(), true),
        );
    }

    /**
     * @return array
     */
    protected function setOperationsDataProvider()
    {
        return array(
            array($this->defaultSetValues()),
        );
    }

    /**
     * @return callable
     */
    protected function alwaysTrueCallable()
    {
        return function () {
            return true;
        };
    }

    /**
     * @return callable
     */
    protected function alwaysFalseCallable()
    {
        return function () {
            return false;
        };
    }

    /**
     * @return callable
     */
    protected function defaultSortedComparator()
    {
        return Comparator::defaultComparator();
    }

    /**
     * @param \Iterator $iterator
     * @param callable  $comparator
     *
     * @return \ArrayIterator
     */
    protected function sortIterator(\Iterator $iterator, callable $comparator = null)
    {
        $array = \iterator_to_array($iterator, true);
        \uasort($array, $comparator === null ? Comparator::defaultComparator() : $comparator);

        return new \ArrayIterator($array);
    }

    /**
     * @return \Cubiche\Core\Enumerable\Tests\Fixtures\Value[]
     */
    protected function defaultValues()
    {
        return array(
            new Value(2),
            new Value(6),
            new Value(5),
            new Value(4),
            new Value(5),
            new Value(1),
            new Value(3),
            new Value(4),
            new Value(6),
            new Value(2),
        );
    }

    /**
     * @return \Cubiche\Core\Enumerable\Tests\Fixtures\Value[]
     */
    protected function defaultSetValues()
    {
        return array(
            new Value(2),
            new Value(3),
            new Value(3),
            new Value(5),
            new Value(2),
        );
    }
}
