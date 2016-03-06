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

use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * ArrayCollection Interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ArrayCollectionInterface extends CollectionInterface, \ArrayAccess
{
    /**
     * Removes an element from the collection by a given key/index.
     *
     * @param mixed $key
     */
    public function removeAt($key);

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
    public function containsKey($key);

    /**
     * Gets the element at the specified key/index.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|int $key
     * @param mixed      $value
     */
    public function set($key, $value);

    /**
     * @param ComparatorInterface $criteria
     */
    public function sort(ComparatorInterface $criteria = null);
}
