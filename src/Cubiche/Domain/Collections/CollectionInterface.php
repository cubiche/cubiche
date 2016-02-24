<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * Collection Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface CollectionInterface extends \Countable, \IteratorAggregate
{
    /**
     * Adds an element at the end of the collection.
     *
     * @param mixed $item
     */
    public function add($item);

    /**
     * @param mixed $item
     */
    public function remove($item);

    /**
     * Clears the collection, removing all elements.
     */
    public function clear();

    /**
     * Checks whether an element is contained in the collection.
     *
     * @param mixed $item
     *
     * @return bool
     */
    public function contains($item);

    /**
     * Checks the existence of an element by a given key/index.
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function exists($key);

    /**
     * Gets the element at the specified key/index.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Find an element collection by a given specification.
     *
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Collections\CollectionInterface
     */
    public function find(SpecificationInterface $specification);

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    public function toArray();

    /**
     * Extracts a slice of $length elements starting at position $offset from the Collection.
     *
     * @param int $offset
     * @param int $length
     *
     * @return \Cubiche\Domain\Collections\CollectionInterface
     */
    public function slice($offset, $length = null);

    /**
     * @param ComparatorInterface $comparator
     *
     * @return \Cubiche\Domain\Collections\CollectionInterface
     */
    public function sorted(ComparatorInterface $comparator);
}
