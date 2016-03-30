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

/**
 * ComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ComparatorTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::comparator()
     */
    protected function comparator()
    {
        return new Comparator();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitComparator';
    }
}
