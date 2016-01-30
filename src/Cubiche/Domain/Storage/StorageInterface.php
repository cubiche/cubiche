<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Storage;

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
     * Returns the value of a key in the store.
     *
     * If the key does not exist in the store, an exception is thrown.
     *
     * @param int|string $key
     *
     * @return mixed
     *
     * @throws ReadException        If the store cannot be read.
     * @throws InvalidKeyException  If the key is not a string or integer.
     * @throws KeyNotFoundException If the key was not found.
     */
    public function getOrFail($key);

    /**
     * Returns the values of multiple keys in the store.
     *
     * The passed default value is returned for keys that don't exist.
     *
     * @param int[]|string[] $keys
     * @param mixed          $default
     *
     * @return array
     *
     * @throws ReadException       If the store cannot be read.
     * @throws InvalidKeyException If a key is not a string or integer.
     */
    public function getMultiple(array $keys, $default = null);

    /**
     * Returns the values of multiple keys in the store.
     *
     * If a key does not exist in the store, an exception is thrown.
     *
     * @param int[]|string[] $keys
     *
     * @return array
     *
     * @throws ReadException        If the store cannot be read.
     * @throws InvalidKeyException  If a key is not a string or integer.
     * @throws KeyNotFoundException If a key was not found.
     */
    public function getMultipleOrFail(array $keys);

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
    public function exists($key);

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
