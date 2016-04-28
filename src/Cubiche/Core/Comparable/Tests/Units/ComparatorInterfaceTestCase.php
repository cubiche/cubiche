<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable\Tests\Units;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Comparable\ComparatorVisitorInterface;
use Cubiche\Core\Comparable\MultiComparator;
use Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase;

/**
 * Comparator Interface Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ComparatorInterfaceTestCase extends VisiteeInterfaceTestCase
{
    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($comparator = $this->newDefaultTestedInstance())
            ->then()
                ->object($comparator)
                    ->isInstanceOf(ComparatorInterface::class)
        ;
    }

    /**
     * Test reverse.
     */
    public function testReverse()
    {
        $this
            /* @var \Cubiche\Core\Comparable\ComparatorInterface $comparator */
            ->given($comparator = $this->newDefaultTestedInstance())
            ->then()
                ->object($comparator->reverse())
                    ->isInstanceOf(ComparatorInterface::class)
        ;
    }

    /**
     * Test compare.
     *
     * @param mixed $a
     * @param mixed $b
     * @param int   $expected
     *
     * @dataProvider compareDataProvider
     */
    public function testCompare($a, $b, $expected)
    {
        $this
            /* @var \Cubiche\Core\Comparable\ComparatorInterface $comparator */
            ->given($comparator = $this->newDefaultTestedInstance())
            ->then()
                ->integer($comparator->compare($a, $b))
                    ->isEqualTo($expected)
        ;

        $this
            ->given($reverse = $comparator->reverse())
            ->then()
                ->integer($reverse->compare($a, $b))
                    ->isEqualTo(-1 * $expected)
        ;
    }

    /**
     * Test orX.
     */
    public function testOrX()
    {
        $this
            /* @var \Cubiche\Core\Comparable\ComparatorInterface $comparator */
            ->given($comparator = $this->newDefaultTestedInstance())
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
            /* @var \Cubiche\Core\Comparable\ComparatorInterface $comparatorMock */
            ->given($comparatorMock = $this->newDefaultMockTestedInstance())
            ->given($comparator = $this->newDefaultTestedInstance())
            ->when($comparatorMock->or($comparator))
                ->mock($comparatorMock)
                    ->call('orX')
                        ->withArguments($comparator)
                        ->once()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function visitorInterface()
    {
        return ComparatorVisitorInterface::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function acceptActualVisitorMethod()
    {
        return 'acceptComparatorVisitor';
    }

    /**
     * @return array
     */
    abstract protected function compareDataProvider();
}
