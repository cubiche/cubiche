<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\Tests\Units\DataSource;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Comparable\Custom;
use Cubiche\Core\Equatable\Tests\Fixtures\EquatableObject;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Collection\DataSource\IteratorDataSource;

/**
 * Iterator Data Source Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class IteratorDataSourceTests extends DataSourceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomDataSource(
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        return new IteratorDataSource(
            $this->generator(rand(10, 20)),
            $searchCriteria,
            $sortCriteria,
            $offset,
            $length
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyDataSource()
    {
        return new IteratorDataSource($this->generator(0));
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return new EquatableObject(1000);
    }

    /**
     * @param int $count
     *
     * @return Generator
     */
    protected function generator($count)
    {
        if ($count > 0) {
            foreach (range(0, $count) as $value) {
                yield new EquatableObject($value);
            }
        }
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->datasource($datasource)
                    ->isInstanceOf(IteratorDataSource::class)
        ;
    }

    /**
     * Test findOne.
     */
    public function testFindOne()
    {
        $this
            ->given($emptyDataSource = $this->emptyDataSource())
            ->when($findResult = $emptyDataSource->findOne())
            ->then
                ->variable($findResult)
                    ->isNull()
        ;

        $this
            ->given($randomDataSource = $this->randomDataSource())
            ->when($findResult = $randomDataSource->findOne())
            ->then
                ->variable($findResult)
                    ->isNotNull()
        ;

        $this
            ->given($datasource = $this->randomDataSource(Criteria::method('value')->gt(2), null, 1, 3))
            ->when($findResult = $datasource->findOne())
            ->then
                ->integer($findResult->value())
                    ->isGreaterThan(2)
        ;

        $this
            ->given(
                $comparator = new Comparator(),
                $sortCriteria = new Custom(function ($a, $b) use ($comparator) {
                    return -1 * $comparator->compare($a, $b);
                }),
                $datasource = $this->randomDataSource(Criteria::method('value')->lt(10), $sortCriteria)
            )
            ->when($findResult = $datasource->findOne())
            ->then
                ->integer($findResult->value())
                    ->isEqualTo(9)
        ;
    }

    /*
     * Test filteredDataSource.
     */
    public function testFilteredDataSource()
    {
        $this
            ->given(
                $searchCriteria = Criteria::false(),
                $emptyDataSource = $this->emptyDataSource()
            )
            ->then
                ->boolean($emptyDataSource->isFiltered())
                    ->isFalse()
            ->and
            ->when($filteredDataSource = $emptyDataSource->filteredDataSource($searchCriteria))
            ->then
                ->boolean($filteredDataSource->isFiltered())
                    ->isTrue()
                ->variable($filteredDataSource->searchCriteria())
                    ->isEqualTo($searchCriteria)
        ;

        $this
            ->given(
                $searchCriteria = Criteria::true(),
                $filteredCriteria = Criteria::false(),
                $randomDataSource = $this->randomDataSource($searchCriteria)
            )
            ->then
                ->boolean($randomDataSource->isFiltered())
                    ->isTrue()
            ->and
            ->when($filteredDataSource = $randomDataSource->filteredDataSource($filteredCriteria))
            ->then
                ->boolean($filteredDataSource->isFiltered())
                    ->isTrue()
                ->variable($filteredDataSource->searchCriteria())
                    ->isEqualTo($searchCriteria->andX($filteredCriteria))
        ;
    }

    /*
     * Test slicedDataSource.
     */
    public function testSlicedDataSource()
    {
        $this
            ->given($emptyDataSource = $this->emptyDataSource())
            ->then
                ->boolean($emptyDataSource->isSliced())
                    ->isFalse()
            ->and
            ->when($slicedDataSource = $emptyDataSource->slicedDataSource(2))
            ->then
                ->boolean($slicedDataSource->isSliced())
                    ->isTrue()
                ->integer($slicedDataSource->offset())
                    ->isEqualTo(2)
                ->variable($slicedDataSource->length())
                    ->isNull()
        ;

        $this
            ->given($randomDataSource = $this->randomDataSource(null, null, 2, 10))
            ->then
                ->boolean($randomDataSource->isSliced())
                    ->isTrue()
                ->integer($randomDataSource->offset())
                    ->isEqualTo(2)
                ->integer($randomDataSource->length())
                    ->isEqualTo(10)
            ->and
            ->when($slicedDataSource = $randomDataSource->slicedDataSource(2, 4))
            ->then
                ->boolean($slicedDataSource->isSliced())
                    ->isTrue()
                ->integer($slicedDataSource->offset())
                    ->isEqualTo(4)
                ->integer($slicedDataSource->length())
                    ->isEqualTo(4)
        ;

        $this
            ->given($randomDataSource = $this->randomDataSource(null, null, 2, 10))
            ->then
                ->boolean($randomDataSource->isSliced())
                    ->isTrue()
                ->integer($randomDataSource->offset())
                    ->isEqualTo(2)
                ->integer($randomDataSource->length())
                    ->isEqualTo(10)
            ->and
            ->when($slicedDataSource = $randomDataSource->slicedDataSource(8, 4))
            ->then
                ->boolean($slicedDataSource->isSliced())
                    ->isTrue()
                ->integer($slicedDataSource->offset())
                    ->isEqualTo(10)
                ->integer($slicedDataSource->length())
                    ->isEqualTo(2)
        ;

        $this
            ->given($randomDataSource = $this->randomDataSource(null, null, 2, 4))
            ->then
                ->boolean($randomDataSource->isSliced())
                    ->isTrue()
                ->variable($randomDataSource->offset())
                    ->isEqualTo(2)
                ->variable($randomDataSource->length())
                    ->isEqualTo(4)
            ->and
            ->when($slicedDataSource = $randomDataSource->slicedDataSource(8, 4))
            ->then
                ->boolean($slicedDataSource->isSliced())
                    ->isTrue()
                ->variable($slicedDataSource->offset())
                    ->isEqualTo(10)
                ->variable($slicedDataSource->length())
                    ->isEqualTo(0)
        ;

        $this
            ->given($randomDataSource = $this->randomDataSource(null, null, 2, 4))
            ->let($iterator = $randomDataSource->getIterator())
            ->then()
                ->object($iterator)
                    ->isInstanceOf(\Traversable::class)
                ->integer(\iterator_count($iterator))
                    ->isEqualTo(4);
    }

    /*
     * Test sortedDataSource.
     */
    public function testSortedDataSource()
    {
        $this
            ->given(
                $sortCriteria = new Comparator(),
                $datasource = $this->randomDataSource()
            )
            ->then
                ->boolean($datasource->isSorted())
                    ->isFalse()
            ->and
            ->when($sortedDataSource = $datasource->sortedDataSource($sortCriteria))
            ->then
                ->boolean($sortedDataSource->isSorted())
                    ->isTrue()
                ->variable($sortedDataSource->sortCriteria())
                    ->isEqualTo($sortCriteria)
        ;
    }
}
