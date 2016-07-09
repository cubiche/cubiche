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
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(ComparatorInterface::class)
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
            ->assert('The comparision is right')
                /* @var \Cubiche\Core\Comparable\ComparatorInterface $comparator */
                ->given($comparator = $this->newDefaultTestedInstance())
                ->then($this->comparision($comparator, $a, $b, $expected));

        $this
            ->assert('The comparator is a callable')
                ->given($result1 = $comparator->compare($a, $b))
                ->when($result2 = $comparator($a, $b))
                ->then()
                    ->variable($result1)
                        ->isEqualTo($result2);
    }

    /**
     * Test reverse method.
     *
     * @param mixed $a
     * @param mixed $b
     * @param int   $expected
     *
     * @dataProvider compareDataProvider
     */
    public function testReverse($a, $b, $expected)
    {
        $this
            ->assert('Reverse is a comparator')
                /* @var \Cubiche\Core\Comparable\ComparatorInterface $comparator */
                ->given($comparator = $this->newDefaultTestedInstance())
                ->when($reverse = $comparator->reverse())
                    ->then()
                        ->object($reverse)
                            ->isInstanceOf(ComparatorInterface::class)
        ;

        $this
            ->assert('Reverse result is the inverse result')
                ->then($this->comparision($reverse, $a, $b, -1 * $expected))
        ;
    }

    /**
     * Test otherwise method.
     *
     * @param callable $otherwise
     * @param mixed    $a
     * @param mixed    $b
     * @param int      $expected
     *
     * @dataProvider otherwiseDataProvider
     */
    public function testOtherwise(callable $otherwise, $a, $b, $expected)
    {
        $this
            ->assert('The otherwise result is a comparator')
                /* @var \Cubiche\Core\Comparable\ComparatorInterface $comparator */
                ->given($comparator = $this->newDefaultTestedInstance())
                ->when($otherwiseComparator = $comparator->otherwise($otherwise))
                    ->object($otherwiseComparator)
                        ->isInstanceOf(ComparatorInterface::class)
        ;

        $this
            ->assert('The otherwise comparator result is right')
                ->let($expected = $this->sign($expected))
                ->then(
                    $this->comparision($otherwiseComparator, $a, $b, $expected == 0 ? $otherwise($a, $b) : $expected)
                )
        ;
    }

    /**
     * @return array
     */
    abstract protected function compareDataProvider();

    /**
     * @return array
     */
    protected function otherwiseDataProvider()
    {
        foreach ($this->compareDataProvider() as $key => $data) {
            yield $key => \array_merge(array($this->newDefaultOtherwiseComparator()), $data);
        }
    }

    /**
     * @return callable
     */
    abstract protected function newDefaultOtherwiseComparator();

    /**
     * @param ComparatorInterface $comparator
     * @param mixed               $a
     * @param mixed               $b
     * @param int                 $expected
     */
    protected function comparision(ComparatorInterface $comparator, $a, $b, $expected)
    {
        $this
            ->given($comparator)
            ->when($result = $comparator->compare($a, $b))
            ->then()
                ->integer($this->sign($result))
                    ->isEqualTo($this->sign($expected))
        ;
    }

    /**
     * @param float|int $number
     *
     * @return int
     */
    protected function sign($number)
    {
        return $number >= 0 ? ($number == 0 ? 0 : 1) : -1;
    }
}
