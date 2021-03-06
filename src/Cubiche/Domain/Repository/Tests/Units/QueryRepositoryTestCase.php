<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Repository\Tests\Units;

use Cubiche\Core\Collections\Tests\Units\CollectionTestCase;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\Repository\Tests\Fixtures\Address;
use Cubiche\Domain\Repository\Tests\Fixtures\AddressId;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * QueryRepositoryTestCase Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class QueryRepositoryTestCase extends CollectionTestCase
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
                'randomRepository',
                function ($size = null) {
                    return $this->randomRepository($size);
                }
            )
            ->setHandler(
                'emptyRepository',
                function () {
                    return $this->emptyRepository();
                }
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function randomCollection($size = null)
    {
        return $this->randomRepository($size);
    }

    /**
     * @param int $size
     *
     * @return QueryRepositoryInterface
     */
    protected function randomRepository($size = null)
    {
        $repository = $this->emptyRepository();
        foreach ($this->randomValues($size) as $randomValue) {
            $repository->persist($randomValue);
        }

        return $repository;
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return $this->emptyRepository();
    }

    /**
     * @return QueryRepositoryInterface
     */
    abstract protected function emptyRepository();

    /**
     * {@inheritdoc}
     */
    protected function randomValue()
    {
        return new Address(
            AddressId::next(),
            'Address-'.\rand(1, 100),
            $this->faker->streetName,
            $this->faker->postcode,
            $this->faker->city,
            Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return new Address(
            AddressId::next(),
            'My Address',
            'My Street',
            'BG23',
            'Bigtown',
            Coordinate::fromLatLng(38.8951, -77.0364)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function comparator()
    {
        return Comparator::by(Criteria::property('name'));
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $this
            ->given($repository = $this->randomRepository())
            ->and($unique = $this->uniqueValue())
            ->when($repository->persist($unique))
            ->then()
                ->object($repository->get($unique->id()))
                    ->isEqualTo($unique);
    }

    /**
     * Test persist.
     */
    public function testPersist()
    {
        $this
            ->given($repository = $this->randomRepository())
            ->and($unique = $this->uniqueValue())
            ->then()
                ->variable($repository->get($unique->id()))
                    ->isNull()
            ->and()
            ->when($repository->persist($unique))
            ->then()
                ->object($repository->get($unique->id()))
                    ->isEqualTo($unique)
        ;

        $this
            ->given($repository = $this->emptyRepository())
            ->and($value = $this->randomValue())
            ->and($name = $value->name())
            ->when(function () use ($repository, $value, $name) {
                $repository->persist($value);
                $value->setName('Ivan');
                $repository->persist($value);
            })
            ->then()
                ->object($other = $repository->findOne(Criteria::property('id')->eq($value->id())))
                    ->string($other->name())
                        ->isEqualTo('Ivan')
        ;
    }

    /**
     * Test persistAll.
     */
    public function testPersistAll()
    {
        $this
            ->given($repository = $this->emptyRepository())
            ->and($unique = $this->uniqueValue())
            ->and($random = $this->randomValue())
            ->then()
                ->variable($repository->get($unique->id()))
                    ->isNull()
                ->variable($repository->get($random->id()))
                    ->isNull()
            ->and()
            ->when($repository->persistAll([$unique, $random]))
            ->then()
                ->object($repository->get($unique->id()))
                    ->isEqualTo($unique)
                ->object($repository->get($random->id()))
                    ->isEqualTo($random)
        ;

        $this
            ->given($repository = $this->emptyRepository())
            ->and($value = $this->randomValue())
            ->and($name = $value->name())
            ->when(function () use ($repository, $value, $name) {
                $repository->persist($value);
                $value->setName('Ivan');
                $repository->persist($value);
            })
            ->then()
                ->object($other = $repository->findOne(Criteria::property('id')->eq($value->id())))
                    ->string($other->name())
                        ->isEqualTo('Ivan')
        ;
    }

    /**
     * Test remove.
     */
    public function testRemove()
    {
        $this
            ->given($repository = $this->randomRepository())
            ->and($unique = $this->uniqueValue())
            ->when($repository->persist($unique))
            ->then()
                ->object($repository->get($unique->id()))
                    ->isEqualTo($unique)
            ->and()
            ->when($repository->remove($unique))
            ->then()
                ->variable($repository->get($unique->id()))
                    ->isNull()
        ;
    }

    /**
     * Test findOne.
     */
    public function testFindOne()
    {
        $this
            ->given($repository = $this->emptyRepository())
            ->and($value = $this->randomValue())
            ->when($repository->persist($value))
            ->then()
                ->object($repository->findOne(Criteria::property('id')->eq($value->id())))
                    ->isEqualTo($value)
        ;
    }

    /**
     * Test getIterator.
     */
    public function testGetIterator()
    {
        $this
            ->given($collection = $this->randomRepository())
            ->then()
                ->object($collection->getIterator())
                    ->isInstanceOf(\Traversable::class)
        ;
    }
}
