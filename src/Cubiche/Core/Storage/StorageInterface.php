<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Storage;

/**
 * Storage interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface StorageInterface
{
    /**
     * Sets the value for a key in the store.
     *
     * @param int|string $key
     * @param mixed      $value
     *
     * @throws WriteException      If the store cannot be written.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function set($key, $value);

    /**
     * Returns the value of key in store.
     *
     * If a key does not exist in the store, the default value passed in the
     * second parameter is returned.
     *
     * @param int|string $key
     * @param mixed      $default
     *
     * @return mixed
     *
     * @throws ReadException       If the store cannot be read.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function get($key, $default = null);

    /**
     * Returns whether a key exists.
     *
     * @param int|string $key
     *
     * @return bool
     *
     * @throws ReadException       If the store cannot be read.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function has($key);

    /**
     * Removes a key from the store.
     *
     * @param int|string $key
     *
     * @return bool
     *
     * @throws WriteException      If the store cannot be written.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function remove($key);

    /**
     * Removes all keys from the store.
     *
     * @throws WriteException If the store cannot be written.
     */
    public function clear();

    /**
     * Returns all keys currently stored in the store.
     *
     * @return array
     *
     * @throws ReadException If the store cannot be read.
     */
    public function keys();
}
