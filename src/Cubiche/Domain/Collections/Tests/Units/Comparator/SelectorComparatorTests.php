<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units\Comparator;

use Cubiche\Domain\Collections\Comparator\Order;
use Cubiche\Domain\Collections\Comparator\SelectorComparator;
use Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Collections\Comparator\ComparatorVisitorInterface;
use Cubiche\Domain\Comparable\ComparatorVisitorInterface as BaseComparatorVisitorInterface;

/**
 * SelectorComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SelectorComparatorTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::comparator()
     */
    protected function comparator()
    {
        return new SelectorComparator(Criteria::property('foo'), Order::ASC());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitSelectorComparator';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::comparatorVisitorInterface()
     */
    protected function comparatorVisitorInterface()
    {
        return ComparatorVisitorInterface::class;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::testCompare()
     */
    public function testCompare()
    {
        $this
            ->given($comparator = $this->comparator())
            ->then()
                ->integer($comparator->compare((object) array('foo' => 1), (object) array('foo' => 2)))
                    ->isEqualTo(-1)
                ->integer($comparator->compare((object) array('foo' => 1), (object) array('foo' => 1)))
                    ->isEqualTo(0)
                ->integer($comparator->compare((object) array('foo' => 1), (object) array('foo' => 0)))
                    ->isEqualTo(1)
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::testReverse()
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
                ->integer($reverseComparator->compare((object) array('foo' => 1), (object) array('foo' => 2)))
                    ->isEqualTo(-1 * $comparator->compare((object) array('foo' => 1), (object) array('foo' => 2)))
                ->integer($reverseComparator->compare((object) array('foo' => 1), (object) array('foo' => 1)))
                    ->isEqualTo(-1 * $comparator->compare((object) array('foo' => 1), (object) array('foo' => 1)))
                ->integer($reverseComparator->compare((object) array('foo' => 1), (object) array('foo' => 0)))
                    ->isEqualTo(-1 * $comparator->compare((object) array('foo' => 1), (object) array('foo' => 0)))
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::testVisit()
     */
    public function testVisit()
    {
        parent::testVisit();

        $this
            ->let($mockClass = '\\mock\\'.BaseComparatorVisitorInterface::class)
            ->given($visitorMock = new $mockClass())
            ->given($comparator = $this->comparator())
            ->exception(function () use ($visitorMock, $comparator) {
                $comparator->accept($visitorMock);
            })
        ;
    }
}
