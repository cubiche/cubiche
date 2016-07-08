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
class ReverseComparator extends Comparator
{
    /**
     * @var ComparatorInterface
     */
    protected $comparator;

    /**
     * @param callable $comparator
     */
    public function __construct(callable $comparator)
    {
        $this->comparator = self::from($comparator);
    }

    /**
     * {@inheritdoc}
     */
    public function compare($a, $b)
    {
        return -1 * $this->reverse()->compare($a, $b);
    }

    /**
     * {@inheritdoc}
     */
    public function reverse()
    {
        return $this->comparator;
    }
}
