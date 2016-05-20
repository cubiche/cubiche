<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection;

use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * Collection Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface CollectionInterface extends \Countable, \IteratorAggregate
{
    /**
     * Get the first element in the collection.
     *
     * @return mixed
     */
    public function first();

    /**
     * Get the last element in the collection.
     *
     * @return mixed
     */
    public function last();

    /**
     * Get the next element in the collection.
     *
     * @return mixed
     */
    public function next();

    /**
     * Get the current element in the collection.
     *
     * @return mixed
     */
    public function current();

    /**
     * Clears the collection, removing all elements.
     */
    public function clear();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    public function toArray();

    /**
     * Sorts the collection according to the order induced by the specified comparator.
     *
     * @param ComparatorInterface $criteria
     */
    public function sort(ComparatorInterface $criteria = null);
}
