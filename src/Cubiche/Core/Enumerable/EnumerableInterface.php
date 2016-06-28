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
 * Enumerable Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface EnumerableInterface extends \Countable, \IteratorAggregate
{
    /**
     * Find all elements that match with a given criteria in this enumerable.
     *
     * @param callable $predicate
     *
     * @return EnumerableInterface
     */
    public function where(callable $predicate);

    /**
     * Returns a new enumerable sorted by the given criteria.
     *
     * @param callable $comparator
     *
     * @return EnumerableInterface
     */
    public function sorted(callable $comparator);

    /**
     * Returns a view of the portion of this enumerable starting at the specified $offset, with size equals to $length.
     *
     * @param int $offset
     * @param int $length
     *
     * @return EnumerableInterface
     */
    public function slice($offset, $length = null);

    /**
     * Gets a native PHP array representation of the enumerable.
     *
     * @return array
     */
    public function toArray();
}
