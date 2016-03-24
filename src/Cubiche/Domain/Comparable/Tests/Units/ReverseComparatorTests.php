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
use Cubiche\Domain\Comparable\ReverseComparator;

/**
 * ReverseComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ReverseComparatorTests extends ComparatorTests
{
    /**
     * @return ComparatorInterface
     */
    protected function comparator()
    {
        $comparator = new Comparator();

        return new ReverseComparator($comparator->reverse());
    }

    /**
     * @return string
     */
    protected function shouldVisitMethod()
    {
        return 'visitReverseComparator';
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($comparator = $this->comparator())
            ->then()
                ->object($comparator)
                    ->isInstanceOf(ReverseComparator::class)
        ;
    }
}
