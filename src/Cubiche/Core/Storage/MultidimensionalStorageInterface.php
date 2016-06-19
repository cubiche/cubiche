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
 * MultidimensionalStorage interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MultidimensionalStorageInterface
{
    /**
     * Append the specified value at the tail of the list stored at key.
     *
     * @param int|string $key
     * @param mixed      $value
     *
     * @throws WriteException      If the store cannot be written.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function push($key, $value);

    /**
     * Removes and returns the last element of the list stored at key.
     *
     * @param int|string $key
     *
     * @throws ReadException       If the store cannot be read.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function pop($key);

    /**
     * Returns all values of the list stored at key.
     *
     * @param int|string $key
     *
     * @return array
     *
     * @throws ReadException       If the store cannot be read.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function all($key);

    /**
     * Return the number of elements in the list stored at key.
     *
     * @param int|string $key
     *
     * @return int
     *
     * @throws ReadException       If the store cannot be read.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function count($key);

    /**
     * Returns the specified elements of the list stored at key.
     * Offset indicates the number of items in the list to skip and
     * length indicates the number of items to return.
     *
     * @param int|string $key
     * @param int        $offset
     * @param int        $length
     *
     * @return array
     *
     * @throws ReadException       If the store cannot be read.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function slice($key, $offset, $length = null);
}
