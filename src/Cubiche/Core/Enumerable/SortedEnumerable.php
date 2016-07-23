<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * Sorted Enumerable Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SortedEnumerable extends EnumerableDecorator
{
    /**
     * @var callable
     */
    protected $comparator;

    /**
     * @var \Iterator
     */
    private $sortedIterator;

    /**
     * @param array|\Traversable $enumerable
     * @param callable           $predicate
     */
    public function __construct($enumerable, callable $comparator = null)
    {
        parent::__construct($enumerable);

        $this->comparator = Comparator::ensure($comparator);
        $this->sortedIterator = null;
    }

    /**
     * @return ComparatorInterface
     */
    public function comparator()
    {
        return $this->comparator;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if ($this->sortedIterator === null) {
            $this->sortedIterator = new \ArrayIterator($this->enumerable()->toArray(true));
            $this->sortedIterator->uasort($this->comparator());
        }

        return $this->sortedIterator;
    }
}
