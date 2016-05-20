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

/**
 * HashMap interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface HashMapInterface extends CollectionInterface
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
     * Replaces the value at the specified position in this map with the specified value.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value);

    /**
     * Returns true if this map contains a mapping for the specified key.
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function containsKey($key);

    /**
     * Returns true if this map maps one or more keys to the specified value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function containsValue($value);

    /**
     * Removes the element at the specified position in this list.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function removeAt($key);

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
}
