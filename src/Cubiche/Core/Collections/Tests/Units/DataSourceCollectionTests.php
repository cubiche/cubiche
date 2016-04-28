<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\Tests\Units;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Collections\DataSource\ArrayDataSource;
use Cubiche\Core\Collections\DataSourceCollection;
use Cubiche\Core\Collections\LazyCollection;

/**
 * DataSourceCollectionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DataSourceCollectionTests extends CollectionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomCollection($size = null)
    {
        return new DataSourceCollection(new ArrayDataSource($this->randomValues($size)));
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new DataSourceCollection(new ArrayDataSource([]));
    }

    /**
     * {@inheritdoc}
     */
    protected function randomValue()
    {
        return \rand(0, 100);
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return 1000;
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->then
                ->collection($collection)
                    ->isInstanceOf(LazyCollection::class)
        ;
    }

    /*
     * Test count.
     */
    public function testCount()
    {
        parent::testCount();

        $this
            ->given($collection = $this->randomCollection())
            ->when($collection->clear())
            ->then
                ->integer($collection->count())
                    ->isEqualTo(0)
        ;
    }

    /*
     * Test getIterator.
     */
    public function testGetIterator()
    {
        parent::testGetIterator();

        $this
            ->given($collection = $this->randomCollection())
            ->when($collection->clear())
            ->then
                ->object($collection->getIterator())
                    ->isInstanceOf(\Traversable::class)
        ;
    }

    /*
     * Test slice.
     */
    public function testSlice()
    {
        parent::testSlice();

        $this
            ->given($collection = $this->randomCollection())
            ->when($collection->clear())
            ->and($slicedCollection = $collection->slice(0, 10))
            ->then
                ->collection($slicedCollection)
                    ->isEmpty()
        ;
    }

    /*
     * Test sorted.
     */
    public function testSorted()
    {
        parent::testSorted();

        $this
            ->given(
                $comparator = new Comparator(),
                $collection = $this->randomCollection()
            )
            ->when($collection->clear())
            ->and($sortedCollection = $collection->sorted($comparator))
            ->then
                ->collection($sortedCollection)
                    ->isSorted()
        ;
    }
}
