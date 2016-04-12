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
 * Multi Comparator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class MultiComparator extends AbstractComparator
{
    /**
     * @var ComparatorInterface
     */
    protected $firstComparator;

    /**
     * @var ComparatorInterface
     */
    protected $secondComparator;

    /**
     * @param ComparatorInterface $firstComparator
     * @param ComparatorInterface $secondComparator
     */
    public function __construct(ComparatorInterface $firstComparator, ComparatorInterface $secondComparator)
    {
        $this->firstComparator = $firstComparator;
        $this->secondComparator = $secondComparator;
    }

    /**
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public function firstComparator()
    {
        return $this->firstComparator;
    }

    /**
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public function secondComparator()
    {
        return $this->secondComparator;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\ComparatorInterface::compare()
     */
    public function compare($a, $b)
    {
        $result = $this->firstComparator()->compare($a, $b);

        return $result !== 0 ? $result : $this->secondComparator()->compare($a, $b);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\ComparatorInterface::reverse()
     */
    public function reverse()
    {
        return new self($this->firstComparator()->reverse(), $this->secondComparator()->reverse());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\Comparator::acceptComparatorVisitor()
     */
    public function acceptComparatorVisitor(ComparatorVisitorInterface $visitor)
    {
        return $visitor->visitMultiComparator($this);
    }
}
