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
use Cubiche\Core\Collections\Tests\Units\CollectionTestCase;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\Repository\Tests\Fixtures\User;
use Cubiche\Domain\Repository\Tests\Fixtures\UserId;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * Repository Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class RepositoryTestCase extends CollectionTestCase
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
     *
     * @see \Cubiche\Core\Collections\Tests\Units\CollectionTestCase::randomCollection()
     */
    protected function randomCollection($size = null)
    {
        return $this->randomRepository($size);
    }

    /**
     * @param int $size
     *
     * @return \Cubiche\Domain\Repository\RepositoryInterface
     */
    protected function randomRepository($size = null)
    {
        $repository = $this->emptyRepository();
        $repository->addAll($this->randomValues($size));

        return $repository;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\Tests\Units\CollectionTestCase::emptyCollection()
     */
    protected function emptyCollection()
    {
        return $this->emptyRepository();
    }

    /**
     * @return RepositoryInterface
     */
    abstract protected function emptyRepository();

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\Tests\Units\CollectionTestCase::randomValue()
     */
    protected function randomValue()
    {
        return new User(UserId::next(), 'User-'.\rand(1, 100), \rand(1, 100));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\Tests\Units\CollectionTestCase::uniqueValue()
     */
    protected function uniqueValue()
    {
        return new User(UserId::next(), 'Methuselah', 1000);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\Tests\Units\CollectionTestCase::comparator()
     */
    protected function comparator()
    {
        return Sort::by(Criteria::property('age'));
    }

    /**
     * Test add.
     */
    public function testAdd()
    {
        parent::testAdd();

        $this
            ->given($repository = $this->randomRepository())
            ->given($id = UserId::next())
            ->then()
                ->exception(function () use ($repository, $id) {
                    $repository->add($id);
                })
                    ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test addAll.
     */
    public function testAddAll()
    {
        parent::testAddAll();

        $this
            ->given($repository = $this->randomRepository())
            ->given($id = UserId::next())
            ->then()
                ->exception(function () use ($repository, $id) {
                    $repository->addAll(array($id));
                })
                    ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test addAll.
     */
    public function testGet()
    {
        $this
            ->given($repository = $this->randomRepository())
            ->given($unique = $this->uniqueValue())
            ->when($repository->add($unique))
            ->then()
                ->object($repository->get($unique->id()))
                    ->isEqualTo($unique);
    }

    /**
     * Test addAll.
     */
    public function testUpdate()
    {
        $this
            ->given($repository = $this->emptyRepository())
            ->given($value = $this->randomValue())
            ->let($age = $value->age())
            ->when(function () use ($repository, $value, $age) {
                $repository->add($value);
                $value->setAge($age + 1);
                $repository->update($value);
            })
                ->object($other = $repository->findOne(Criteria::property('id')->eq($value->id())))
                ->integer($other->age())
                    ->isEqualTo($age + 1);
    }
}
