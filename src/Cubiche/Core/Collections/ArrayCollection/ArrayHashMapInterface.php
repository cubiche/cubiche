<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\ArrayCollection;

use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Collections\HashMapInterface;
use Cubiche\Core\Collections\SetInterface;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * ArrayHashMap interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ArrayHashMapInterface extends HashMapInterface
{
    /**
     * Returns the value to which the specified key is mapped,
     * or null if this map contains no mapping for the key.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Returns true if this map maps one or more keys to the specified value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function containsValue($value);

    /**
     * Returns a set of the keys contained in this map.
     *
     * @return SetInterface
     */
    public function keys();

    /**
     * Returns a collection of the values contained in this map.
     *
     * @return CollectionInterface
     */
    public function values();

    /**
     * Sorts the map keys according to the order induced by the specified comparator.
     *
     * @param ComparatorInterface $criteria
     */
    public function sort(ComparatorInterface $criteria = null);
}
