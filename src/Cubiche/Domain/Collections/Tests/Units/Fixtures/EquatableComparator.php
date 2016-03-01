<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units\Fixtures;

use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Comparable\ComparatorVisitorInterface;
use Cubiche\Domain\Comparable\MultiComparator;

/**
 * EquatableComparator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EquatableComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::compare()
     */
    public function compare($a, $b)
    {
        return $a->value() < $b->value() ? 1 : ($a->value() == $b->value() ? 0 : -1);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::orX()
     */
    public function orX(ComparatorInterface $other)
    {
        return new MultiComparator($this, $other);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::accept()
     */
    public function accept(ComparatorVisitorInterface $visitor)
    {
        return $visitor->visitComparator($this);
    }
}
