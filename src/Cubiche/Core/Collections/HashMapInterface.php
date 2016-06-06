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

/**
 * HashMap interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface HashMapInterface extends CollectionInterface
{
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
     * Removes the element at the specified position in this list.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function removeAt($key);
}
