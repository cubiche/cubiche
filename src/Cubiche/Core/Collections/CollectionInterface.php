<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections;

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Collection Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface CollectionInterface extends \Countable, \IteratorAggregate
{
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
     * Returns a new collection sorted by the given criteria.
     *
     * @param ComparatorInterface $criteria
     *
     * @return CollectionInterface
     */
    public function sorted(ComparatorInterface $criteria);

    /**
     * Returns a view of the portion of this collection starting at the specified $offset, with size equals to $length.
     *
     * @param int $offset
     * @param int $length
     *
     * @return CollectionInterface
     */
    public function slice($offset, $length = null);

    /**
     * Find all elements that match with a given specification in this collection.
     *
     * @param SpecificationInterface $criteria
     *
     * @return CollectionInterface
     */
    public function find(SpecificationInterface $criteria);
}
