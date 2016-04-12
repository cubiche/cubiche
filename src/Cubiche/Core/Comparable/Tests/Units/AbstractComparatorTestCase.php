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

use Cubiche\Core\Comparable\AbstractComparator;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\Tests\Fixtures\ComparableObject;

/**
 * Abstract Comparator Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class AbstractComparatorTestCase extends ComparatorInterfaceTestCase
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
                    ->isInstanceOf(AbstractComparator::class)
        ;
    }

    /**
     * Test __call.
     */
    public function testMagicCall()
    {
        parent::testMagicCall();

        $this
            ->given($comparatorMock = $this->newDefaultMockTestedInstance())
            ->exception(function () use ($comparatorMock) {
                $comparatorMock->foo();
            })
                ->isInstanceOf(\BadMethodCallException::class)
            ;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\Tests\Units\ComparatorInterfaceTestCase::compareDataProvider()
     */
    protected function compareDataProvider()
    {
        return array(
            array(1, 2, -1),
            array(1, 1, 0),
            array(1, 0, 1),
            array(new ComparableObject(1), new ComparableObject(2), -1),
            array(new ComparableObject(1), new ComparableObject(1), 0),
            array(new ComparableObject(1), new ComparableObject(0), 1),
        );
    }
}
