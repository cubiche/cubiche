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
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ReverseComparator extends AbstractComparator
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
        return $this->comparator->compare($b, $a);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::reverse()
     */
    public function reverse()
    {
        return $this->comparator();
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
