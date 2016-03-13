<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units\DataSource;

use Cubiche\Domain\Collections\DataSource\ArrayDataSource;
use Cubiche\Domain\Collections\Tests\Units\Fixtures\ReverseComparator;
use Cubiche\Domain\Collections\Tests\Units\Fixtures\EquatableObject;
use Cubiche\Domain\Collections\Tests\Units\Fixtures\FakeSpecification;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\SpecificationInterface;

/**
 * ArrayDataSourceTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayDataSourceTests extends DataSourceTestCase
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
        $items = array();
        foreach (range(0, rand(10, 20)) as $value) {
            $items[] = new EquatableObject($value);
        }

        return new ArrayDataSource($items, $searchCriteria, $sortCriteria, $offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyDataSource()
    {
        return new ArrayDataSource(array());
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return new EquatableObject(1000);
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
                    ->isInstanceOf(ArrayDataSource::class)
        ;
    }

    /*
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
                $sortCriteria = new ReverseComparator(),
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
                $searchCriteria = new FakeSpecification(),
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
                $searchCriteria = new FakeSpecification(true),
                $filteredCriteria = new FakeSpecification(false),
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
                ->variable($slicedDataSource->offset())
                    ->isEqualTo(2)
                ->variable($slicedDataSource->length())
                    ->isNull()
        ;

        $this
            ->given($randomDataSource = $this->randomDataSource(null, null, 2, 10))
            ->then
                ->boolean($randomDataSource->isSliced())
                    ->isTrue()
                ->variable($randomDataSource->offset())
                    ->isEqualTo(2)
                ->variable($randomDataSource->length())
                    ->isEqualTo(10)
            ->and
            ->when($slicedDataSource = $randomDataSource->slicedDataSource(2, 4))
            ->then
                ->boolean($slicedDataSource->isSliced())
                    ->isTrue()
                ->variable($slicedDataSource->offset())
                    ->isEqualTo(4)
                ->variable($slicedDataSource->length())
                    ->isEqualTo(4)
        ;

        $this
            ->given($randomDataSource = $this->randomDataSource(null, null, 2, 10))
            ->then
                ->boolean($randomDataSource->isSliced())
                    ->isTrue()
                ->variable($randomDataSource->offset())
                    ->isEqualTo(2)
                ->variable($randomDataSource->length())
                    ->isEqualTo(10)
            ->and
            ->when($slicedDataSource = $randomDataSource->slicedDataSource(8, 4))
            ->then
                ->boolean($slicedDataSource->isSliced())
                    ->isTrue()
                ->variable($slicedDataSource->offset())
                    ->isEqualTo(10)
                ->variable($slicedDataSource->length())
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
    }

    /*
     * Test sortedDataSource.
     */
    public function testSortedDataSource()
    {
        $this
            ->given(
                $sortCriteria = new ReverseComparator(),
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
