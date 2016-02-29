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
 * Multi Comparator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class MultiComparator extends Comparator
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
     * @param ComparatorInterface $secondcomparator
     */
    public function __construct(ComparatorInterface $firstComparator, ComparatorInterface $secondComparator)
    {
        $this->firstComparator = $firstComparator;
        $this->secondComparator = $secondComparator;
    }

    /**
     * @return \Cubiche\Domain\Comparable\ComparatorInterface
     */
    public function firstComparator()
    {
        return $this->firstComparator;
    }

    /**
     * @return \Cubiche\Domain\Comparable\ComparatorInterface
     */
    public function secondComparator()
    {
        return $this->secondComparator;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::compare()
     */
    public function compare($a, $b)
    {
        $result = $this->firstComparator()->compare($a, $b);

        return $result !== 0 ? $result : $this->secondComparator()->compare($a, $b);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Comparator::accept()
     */
    public function accept(ComparatorVisitorInterface $visitor)
    {
        return $visitor->visitMultiComparator($this);
    }
}
