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

use Cubiche\Core\Comparable\Sort;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\Repository\Tests\Fixtures\User;
use Cubiche\Domain\Repository\Tests\Fixtures\UserId;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * RepositoryTestCase Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class RepositoryTestCase extends TestCase
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
     * @param int $size
     *
     * @return RepositoryInterface
     */
    protected function randomRepository($size = null)
    {
        $repository = $this->emptyRepository();
        $repository->persistAll($this->randomValues($size));

        return $repository;
    }

    /**
     * @return RepositoryInterface
     */
    abstract protected function emptyRepository();

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
     * {@inheritdoc}
     */
    protected function randomValue()
    {
        return new User(UserId::next(), 'User-'.\rand(1, 100), \rand(1, 100));
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return new User(UserId::next(), 'Methuselah', 1000);
    }

    /**
     * {@inheritdoc}
     */
    protected function comparator()
    {
        return Sort::by(Criteria::property('age'));
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
            ->given($repository = $this->randomRepository())
            ->given($id = UserId::next())
            ->then()
                ->exception(function () use ($repository, $id) {
                    $repository->persist($id);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;

        $this
            ->given($repository = $this->emptyRepository())
            ->and($value = $this->randomValue())
            ->and($age = $value->age())
            ->when(function () use ($repository, $value, $age) {
                $repository->persist($value);
                $value->setAge($age + 1);
                $repository->persist($value);
            })
            ->then()
                ->object($other = $repository->get($value->id()))
                    ->integer($other->age())
                        ->isEqualTo($age + 1)
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
            ->given($repository = $this->randomRepository())
            ->given($id = UserId::next())
            ->then()
                ->exception(function () use ($repository, $id) {
                    $repository->persistAll([$id]);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;

        $this
            ->given($repository = $this->emptyRepository())
            ->and($value = $this->randomValue())
            ->and($age = $value->age())
            ->when(function () use ($repository, $value, $age) {
                $repository->persistAll([$value]);
                $value->setAge($age + 1);
                $repository->persistAll([$value]);
            })
            ->then()
                ->object($other = $repository->get($value->id()))
                    ->integer($other->age())
                        ->isEqualTo($age + 1)
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

        $this
            ->given($repository = $this->randomRepository())
            ->given($id = UserId::next())
            ->then()
                ->exception(function () use ($repository, $id) {
                    $repository->remove($id);
                })->isInstanceOf(\InvalidArgumentException::class)
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
