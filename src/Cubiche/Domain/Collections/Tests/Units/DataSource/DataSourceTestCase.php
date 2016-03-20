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

use Cubiche\Domain\Collections\DataSource\DataSourceInterface;
use Cubiche\Domain\Collections\Tests\Units\TestCase;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\SpecificationInterface;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * DataSourceTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class DataSourceTestCase extends TestCase
{
    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param \Closure  $reflectionClassFactory
     * @param \Closure  $phpExtensionFactory
     * @param Analyzer  $analyzer
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        \Closure $reflectionClassFactory = null,
        \Closure $phpExtensionFactory = null,
        Analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );

        $this->getAssertionManager()
            ->setHandler(
                'randomDataSource',
                function (
                    SpecificationInterface $searchCriteria = null,
                    ComparatorInterface $sortCriteria = null,
                    $offset = null,
                    $length = null
                ) {
                    return $this->randomDataSource(
                        $searchCriteria,
                        $sortCriteria,
                        $offset,
                        $length
                    );
                }
            )
            ->setHandler(
                'emptyDataSource',
                function () {
                    return $this->emptyDataSource();
                }
            )
            ->setHandler(
                'uniqueValue',
                function () {
                    return $this->uniqueValue();
                }
            )
        ;
    }

    /**
     * @param SpecificationInterface|null $searchCriteria
     * @param ComparatorInterface|null    $sortCriteria
     * @param null                        $offset
     * @param null                        $length
     *
     * @return mixed
     */
    abstract protected function randomDataSource(
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    );

    /**
     * @return DataSourceInterface
     */
    abstract protected function emptyDataSource();

    /**
     * @return mixed
     */
    abstract protected function uniqueValue();

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->datasource($datasource)
                    ->isInstanceOf(DataSourceInterface::class)
        ;
    }

    /*
     * Test count.
     */
    public function testCount()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->integer($datasource->count())
                    ->isGreaterThan(0)
        ;
    }

    /*
     * Test length.
     */
    public function testLength()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->variable($datasource->length())
                    ->isNull()
            ->given($datasource = $this->randomDataSource(null, null, null, 10))
            ->then
                ->variable($datasource->length())
                    ->isEqualTo(10)
        ;
    }

    /*
     * Test offset.
     */
    public function testOffset()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->variable($datasource->offset())
                    ->isNull()
            ->given($datasource = $this->randomDataSource(null, null, 8))
            ->then
                ->variable($datasource->offset())
                    ->isEqualTo(8)
        ;
    }

    /*
     * Test searchCriteria.
     */
    public function testSearchCriteria()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->variable($datasource->searchCriteria())
                    ->isNull()
            ->given(
                $searchCriteria = Criteria::false(),
                $datasource = $this->randomDataSource($searchCriteria)
            )
            ->then
                ->variable($datasource->searchCriteria())
                    ->isEqualTo($searchCriteria)
        ;
    }

    /*
     * Test sortCriteria.
     */
    public function testSortCriteria()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->variable($datasource->sortCriteria())
                    ->isNull()
            ->given(
                $sortCriteria = new Comparator(),
                $datasource = $this->randomDataSource(null, $sortCriteria)
            )
            ->then
                ->variable($datasource->sortCriteria())
                    ->isEqualTo($sortCriteria)
        ;
    }

    /*
     * Test isSorted.
     */
    public function testIsSorted()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->boolean($datasource->isSorted())
                    ->isFalse()
            ->given(
                $sortCriteria = new Comparator(),
                $datasource = $this->randomDataSource(null, $sortCriteria)
            )
            ->then
                ->boolean($datasource->isSorted())
                    ->isTrue()
        ;
    }

    /*
     * Test isFiltered.
     */
    public function testIsFiltered()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->boolean($datasource->isFiltered())
                    ->isFalse()
            ->given(
                $searchCriteria = Criteria::false(),
                $datasource = $this->randomDataSource($searchCriteria)
            )
            ->then
                ->boolean($datasource->isFiltered())
                    ->isTrue()
        ;
    }

    /*
     * Test isSliced.
     */
    public function testIsSliced()
    {
        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->boolean($datasource->isSliced())
                    ->isFalse()
            ->given($datasource = $this->randomDataSource(null, null, 8))
            ->then
                ->boolean($datasource->isSliced())
                    ->isTrue()
            ->given($datasource = $this->randomDataSource(null, null, null, 10))
            ->then
                ->boolean($datasource->isSliced())
                    ->isTrue()
        ;
    }
}
