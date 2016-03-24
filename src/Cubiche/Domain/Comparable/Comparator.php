<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Comparable;

/**
 * Comparator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Comparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::compare()
     */
    public function compare($a, $b)
    {
        if ($a instanceof ComparableInterface) {
            return $a->compareTo($b);
        }

        return $a < $b ? -1 : ($a == $b ? 0 : 1);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::reverse()
     */
    public function reverse()
    {
        return new ReverseComparator($this);
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
