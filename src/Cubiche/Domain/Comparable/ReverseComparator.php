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
 * ReverseComparator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ReverseComparator extends Comparator
{
    /**
     * @var ComparatorInterface
     */
    protected $comparator;

    /**
     * ReverseComparator constructor.
     *
     * @param ComparatorInterface $comparator
     */
    public function __construct(ComparatorInterface $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * @return \Cubiche\Domain\Comparable\ComparatorInterface
     */
    public function comparator()
    {
        return $this->comparator;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::compare()
     */
    public function compare($a, $b)
    {
        return -1 * $this->comparator->compare($a, $b);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::accept()
     */
    public function accept(ComparatorVisitorInterface $visitor)
    {
        return $visitor->visitReverseComparator($this);
    }
}
