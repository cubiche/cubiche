<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Comparable\Tests\Units;

use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Comparable\ComparatorVisitorInterface;
use Cubiche\Domain\Comparable\MultiComparator;
use Cubiche\Domain\Comparable\Tests\Fixtures\ComparableObject;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;
use Cubiche\Domain\Comparable\AbstractComparator;

/**
 * Comparator Test Case class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ComparatorTestCase extends TestCase
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
                'comparator',
                function () {
                    return $this->comparator();
                }
            )
            ->setHandler(
                'shouldVisitMethod',
                function () {
                    return $this->shouldVisitMethod();
                }
            )
        ;
    }

    /**
     * @return ComparatorInterface
     */
    abstract protected function comparator();

    /**
     * @return string
     */
    abstract protected function shouldVisitMethod();

    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($comparator = $this->comparator())
            ->then()
                ->object($comparator)
                    ->isInstanceOf(ComparatorInterface::class)
        ;
    }

    /**
     * Test compare.
     */
    public function testCompare()
    {
        $this
            ->given($comparator = $this->comparator())
            ->then()
                ->integer($comparator->compare(1, 2))
                    ->isEqualTo(-1)
                ->integer($comparator->compare(1, 1))
                    ->isEqualTo(0)
                ->integer($comparator->compare(1, 0))
                    ->isEqualTo(1)
        ;

        $this
            ->given($comparator)
            ->then()
                ->integer($comparator->compare(new ComparableObject(1), new ComparableObject(2)))
                    ->isEqualTo(-1)
                ->integer($comparator->compare(new ComparableObject(1), new ComparableObject(1)))
                    ->isEqualTo(0)
                ->integer($comparator->compare(new ComparableObject(1), new ComparableObject(0)))
                    ->isEqualTo(1)
        ;
    }

    /**
     * Test reverse.
     */
    public function testReverse()
    {
        $this
            ->given($comparator = $this->comparator())
            ->let($reverseComparator = $comparator->reverse())
            ->then()
                ->object($reverseComparator)
                    ->isInstanceOf(ComparatorInterface::class)
        ;

        $this
            ->given($comparator)
            ->given($reverseComparator)
            ->then()
                ->integer($reverseComparator->compare(1, 2))
                    ->isEqualTo(-1 * $comparator->compare(1, 2))
                ->integer($reverseComparator->compare(1, 1))
                    ->isEqualTo(-1 * $comparator->compare(1, 1))
                ->integer($reverseComparator->compare(1, 0))
                    ->isEqualTo(-1 * $comparator->compare(1, 0))
        ;

        $this
            ->given($comparator)
            ->given($reverseComparator)
            ->then()
                ->integer($reverseComparator->compare(new ComparableObject(1), new ComparableObject(2)))
                    ->isEqualTo(-1 * $comparator->compare(new ComparableObject(1), new ComparableObject(2)))
                ->integer($reverseComparator->compare(new ComparableObject(1), new ComparableObject(1)))
                    ->isEqualTo(-1 * $comparator->compare(new ComparableObject(1), new ComparableObject(1)))
                ->integer($reverseComparator->compare(new ComparableObject(1), new ComparableObject(0)))
                    ->isEqualTo(-1 * $comparator->compare(new ComparableObject(1), new ComparableObject(0)))
        ;
    }

    /**
     * Test orX.
     */
    public function testOrX()
    {
        $this
            ->given($comparator = $this->comparator())
            ->then()
            ->when($result = $comparator->orX($comparator->reverse()))
                ->object($result)
                    ->isInstanceOf(MultiComparator::class)
        ;
    }

    /*
     * Test __call.
     */
    public function testMagicCall()
    {
        $this
            ->let($mockClass = '\\mock\\'.AbstractComparator::class)
            ->given($comparatorMock = new $mockClass())
            ->given($comparator = $this->comparator())
            ->when($comparatorMock->or($comparator))
                ->mock($comparatorMock)
                    ->call('orX')
                        ->withArguments($comparator)->once()

            ->exception(function () use ($comparatorMock) {
                $comparatorMock->foo();
            })
                ->isInstanceOf(\BadMethodCallException::class)
            ;
    }

    /**
     * Test visit.
     */
    public function testVisit()
    {
        $shouldVisitMethod = $this->shouldVisitMethod();

        $this
            ->let($mockClass = '\\mock\\'.$this->comparatorVisitorInterface())
            ->given($visitorMock = new $mockClass())
            ->calling($visitorMock)
            ->methods(
                function ($method) use ($shouldVisitMethod) {
                    return $method === strtolower($shouldVisitMethod);
                }
            )
            ->return = 25
        ;

        $this
            ->given($comparator = $this->comparator())
            ->when($result = $comparator->accept($visitorMock))
                ->mock($visitorMock)
                    ->call($shouldVisitMethod)
                        ->withArguments($comparator)
                        ->once()
                ->integer($result)
                    ->isEqualTo(25)
        ;
    }

    /**
     * @return string
     */
    protected function comparatorVisitorInterface()
    {
        return ComparatorVisitorInterface::class;
    }
}
