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
 * ExpirableStorage interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ExpirableStorageInterface
{
    /**
     * Sets the value for a key in the store for a given ttl.
     *
     * @param int|string $key
     * @param mixed      $value
     * @param int        $ttl
     *
     * @throws WriteException      If the store cannot be written.
     * @throws InvalidKeyException If the key is not a string or integer.
     */
    public function set($key, $value, $ttl = null);
}
