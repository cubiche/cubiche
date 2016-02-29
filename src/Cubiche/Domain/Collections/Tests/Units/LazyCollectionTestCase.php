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

use Closure;
use Cubiche\Domain\Collections\CollectionInterface;
use Cubiche\Domain\Collections\LazyCollection;
use Cubiche\Domain\Tests\Units\TestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;

/**
 * LazyCollectionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class LazyCollectionTestCase extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        Closure $reflectionClassFactory = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );
        $this->getAssertionManager()
            ->setHandler(
                'createCollection',
                function (array $items = array()) {
                    return $this->createCollection($items);
                }
            )
        ;
    }

    /**
     * @param array $items
     *
     * @return LazyCollection
     */
    abstract protected function createCollection(array $items = array());

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given(
                $collection = $this->createCollection()
            )
            ->then
                ->collection($collection)
                ->isInstanceOf(LazyCollection::class)
                ->isInstanceOf(CollectionInterface::class)
        ;
    }

    /*
     * Test add is lazy.
     */
    public function testAdd()
    {
        $this
            ->given(
                $collection = $this->createCollection()
            )
            ->when($collection->add(1))
            ->then
                ->collection($collection)
                    ->isEmpty()
                    ->size
                        ->isEqualTo(0)
        ;
    }

    /*
     * Test remove is lazy.
     */
    public function testRemove()
    {
        $this
            ->given(
                $collection = $this->createCollection(array(1, 2, 3))
            )
            ->when($collection->remove(2))
            ->then
                ->collection($collection)
                    ->size
                        ->isEqualTo(3)
        ;
    }

    /*
     * Test clear is lazy.
     */
    public function testClear()
    {
        $this
            ->given(
                $collection = $this->createCollection(array(1, 2, 3))
            )
            ->when($collection->clear())
            ->then
                ->collection($collection)
                    ->size
                        ->isEqualTo(3)
        ;
    }

    /*
     * Test count is lazy.
     */
    public function testCount()
    {
        $this
            ->given(
                $collection = $this->createCollection(array(1, 2, 3))
            )
            ->then
                ->variable($collection->count())
                    ->isEqualTo(3)
            ->and
            ->when($collection->add(7))
            ->then
                ->variable($collection->count())
                    ->isEqualTo(3)
        ;
    }
}
