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
 * Multi Comparator class.
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
     * @param callable $firstComparator
     * @param callable $secondComparator
     */
    public function __construct(callable $firstComparator, callable $secondComparator)
    {
        $this->firstComparator = self::from($firstComparator);
        $this->secondComparator = self::from($secondComparator);
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
     */
    public function compare($a, $b)
    {
        $result = $this->firstComparator()->compare($a, $b);

        return $result !== 0 ? $result : $this->secondComparator()->compare($a, $b);
    }

    /**
     * {@inheritdoc}
     */
    public function reverse()
    {
        return new self($this->firstComparator()->reverse(), $this->secondComparator()->reverse());
    }
}
