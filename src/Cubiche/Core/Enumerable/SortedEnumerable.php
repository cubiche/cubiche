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
     * @param EnumerableInterface $enumerable
     * @param callable            $predicate
     */
    public function __construct(EnumerableInterface $enumerable, callable $comparator)
    {
        parent::__construct($enumerable);

        $this->comparator = $comparator;
        $this->sortedIterator = null;
    }

    /**
     * @return callable
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
            $this->sortedIterator = new \ArrayIterator($this->enumerable()->toArray());
            $this->sortedIterator->uasort($this->comparator());
        }

        return $this->sortedIterator;
    }
}
