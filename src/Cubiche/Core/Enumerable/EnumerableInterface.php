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
 * Enumerable interface.
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
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function where(callable $predicate);

    /**
     * Returns a new enumerable sorted by the given criteria.
     *
     * @param callable $comparator
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function sorted(callable $comparator = null);

    /**
     * Returns a view of the portion of this enumerable starting at the specified $offset, with size equals to $length.
     *
     * @param int $offset
     * @param int $length
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function slice($offset, $length = null);

    /**
     * Skips elements up to a specified position in a sequence.
     *
     * @param int $count
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function skip($count);

    /**
     * Takes elements up to a specified position in a sequence.
     *
     * @param int $length
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function limit($length);

    /**
     * Determines whether all the elements in a sequence satisfy a condition.
     *
     * @param callable $predicate
     *
     * @return bool
     */
    public function all(callable $predicate);

    /**
     * Determines whether any elements in a sequence satisfy a condition.
     *
     * @param callable $predicate
     *
     * @return bool
     */
    public function any(callable $predicate);

    /**
     * Determines whether at least a numbers of elements in a sequence satisfy a condition.
     *
     * @param int      $count
     * @param callable $predicate
     *
     * @return bool
     */
    public function atLeast($count, callable $predicate);

    /**
     * Determines whether a sequence contains a specified element.
     *
     * @param mixed    $value
     * @param callable $equalityComparer
     *
     * @return bool
     */
    public function contains($value, callable $equalityComparer = null);

    /**
     * Removes duplicate values from a sequence.
     *
     * @param callable $equalityComparer
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function distinct(callable $equalityComparer = null);

    /**
     * Returns the set difference, which means the elements of one sequence that do not appear in a second sequence.
     *
     * @param array|\Traversable $enumerable
     * @param callable           $equalityComparer
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function except($enumerable, callable $equalityComparer = null);

    /**
     * Returns the set intersection, which means elements that appear in each of two sequences.
     *
     * @param array|\Traversable $enumerable
     * @param callable           $equalityComparer
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function intersect($enumerable, callable $equalityComparer = null);

    /**
     * Returns the set union, which means unique elements that appear in either of two sequences.
     *
     * @param array|\Traversable $enumerable
     * @param callable           $equalityComparer
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function union($enumerable, callable $equalityComparer = null);

    /**
     * Gets a native PHP array representation of the enumerable.
     *
     * @param bool $associative
     *
     * @return array
     */
    public function toArray($associative = false);
}
