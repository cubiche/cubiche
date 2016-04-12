<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable;

/**
 * Reverse Comparator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ReverseComparator extends AbstractComparator
{
    /**
     * @var ComparatorInterface
     */
    protected $comparator;

    /**
     * Reverse Comparator constructor.
     *
     * @param ComparatorInterface $comparator
     */
    public function __construct(ComparatorInterface $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public function comparator()
    {
        return $this->comparator;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\ComparatorInterface::compare()
     */
    public function compare($a, $b)
    {
        return -1 * $this->comparator->compare($a, $b);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\ComparatorInterface::reverse()
     */
    public function reverse()
    {
        return $this->comparator();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\ComparatorInterface::acceptComparatorVisitor()
     */
    public function acceptComparatorVisitor(ComparatorVisitorInterface $visitor)
    {
        return $visitor->visitReverseComparator($this);
    }
}
