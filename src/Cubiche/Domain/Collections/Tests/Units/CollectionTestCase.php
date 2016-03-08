<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units;

use Cubiche\Domain\Collections\CollectionInterface;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Specification\Criteria;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * CollectionTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class CollectionTestCase extends TestCase
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
                'randomCollection',
                function (array $items = array()) {
                    return $this->randomCollection($items);
                }
            )
            ->setHandler(
                'emptyCollection',
                function () {
                    return $this->emptyCollection();
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
     * @param array $items
     *
     * @return CollectionInterface
     */
    abstract protected function randomCollection(array $items = array());

    /**
     * @return CollectionInterface
     */
    abstract protected function emptyCollection();

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
            ->given($collection = $this->randomCollection())
            ->then()
                ->collection($collection)
                    ->isInstanceOf(CollectionInterface::class)
        ;

        $this->variable($a = true)->given($b = null)->variable($b)->isNull();
    }

    /*
     * Test add.
     */
    public function testAdd()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $emptyCollection = $this->emptyCollection()
            )
            ->then
                ->collection($emptyCollection)
                    ->notContains($unique)
            ->and
            ->when($emptyCollection->add($unique))
            ->then
                ->collection($emptyCollection)
                    ->contains($unique)
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $randomCollection = $this->randomCollection()
            )
            ->then
                ->collection($randomCollection)
                    ->notContains($unique)
            ->and
            ->when($randomCollection->add($unique))
            ->then
                ->collection($randomCollection)
                    ->contains($unique)
        ;
    }

    /*
     * Test remove.
     */
    public function testRemove()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $emptyCollection = $this->emptyCollection()
            )
            ->when($emptyCollection->add($unique))
            ->then
                ->collection($emptyCollection)
                    ->contains($unique)
            ->and
            ->when($emptyCollection->remove($unique))
            ->then
                ->collection($emptyCollection)
                    ->notContains($unique)
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $randomCollection = $this->randomCollection()
            )
            ->when($randomCollection->add($unique))
            ->then
                ->collection($randomCollection)
                    ->contains($unique)
            ->and
            ->when($randomCollection->remove($unique))
            ->then
                ->collection($randomCollection)
                    ->notContains($unique)
        ;
    }

    /*
     * Test clear.
     */
    public function testClear()
    {
        $this
            ->given($randomCollection = $this->randomCollection())
            ->then
                ->collection($randomCollection)
                    ->isNotEmpty()
            ->and
            ->when($randomCollection->clear())
            ->then
                ->collection($randomCollection)
                    ->isEmpty()
        ;
    }

    /*
     * Test count.
     */
    public function testCount()
    {
        $this
            ->given($collection = $this->randomCollection(array($this->uniqueValue())))
            ->then
                ->integer($collection->count())
                    ->isEqualTo(1)
        ;
    }

    /*
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

    /*
     * Test find.
     */
    public function testFind()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::eq($unique),
                $emptyCollection = $this->emptyCollection()
            )
            ->when($findResult = $emptyCollection->find($criteria))
            ->then
                ->collection($findResult)
                    ->isEmpty()
            ->and
            ->when(
                $emptyCollection->add($unique),
                $findResult = $emptyCollection->find($criteria)
            )
            ->then
                ->collection($findResult)
                    ->contains($unique)
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::eq($unique),
                $randomCollection = $this->randomCollection()
            )
            ->when($findResult = $randomCollection->find($criteria))
            ->then
                ->collection($findResult)
                    ->isEmpty()
            ->and
            ->when(
                $randomCollection->add($unique),
                $findResult = $randomCollection->find($criteria)
            )
            ->then
                ->collection($findResult)
                    ->contains($unique)
        ;
    }

    /*
     * Test findOne.
     */
    public function testFindOne()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::eq($unique),
                $emptyCollection = $this->emptyCollection()
            )
            ->when($findResult = $emptyCollection->findOne($criteria))
            ->then
                ->variable($findResult)
                    ->isNull()
            ->and
            ->when(
                $emptyCollection->add($unique),
                $findResult = $emptyCollection->findOne($criteria)
            )
            ->then
                ->variable($findResult)
                    ->isEqualTo($unique)
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::eq($unique),
                $randomCollection = $this->randomCollection()
            )
            ->when($findResult = $randomCollection->findOne($criteria))
            ->then
                ->variable($findResult)
                    ->isNull()
            ->and
            ->when(
                $randomCollection->add($unique),
                $findResult = $randomCollection->findOne($criteria)
            )
            ->then
                ->variable($findResult)
                    ->isEqualTo($unique)
        ;
    }

    /*
     * Test toArray.
     */
    public function testToArray()
    {
        $this
            ->given($collection = $this->randomCollection(array($this->uniqueValue())))
            ->when($array = $collection->toArray())
            ->then
                ->array($array)
                    ->isEqualTo($array)
        ;
    }

    /*
     * Test slice.
     */
    public function testSlice()
    {
        $this
            ->given(
                $collection = $this->randomCollection(),
                $count = $collection->count(),
                $offset = rand(0, $count / 2),
                $length = rand($count / 2, $count),
                $maxCount = max([$count - $offset, 0])
            )
            ->when($slicedCollection = $collection->slice($offset, $length))
            ->then
                ->collection($slicedCollection)
                    ->size
                        ->isEqualTo(min($maxCount, $length))
        ;
    }

    /*
     * Test sorted.
     */
    public function testSorted()
    {
        $this
            ->given(
                $comparator = new Comparator(),
                $collection = $this->randomCollection()
            )
            ->when($sortedCollection = $collection->sorted($comparator))
            ->then
                ->collection($sortedCollection)
                    ->isSorted()
        ;
    }
}
