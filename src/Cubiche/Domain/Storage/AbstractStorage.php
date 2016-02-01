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

use Cubiche\Domain\Storage\Exception\InvalidKeyException;

/**
 * AbstractStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractStorage implements StorageInterface
{
    /**
     * Validates that a key is valid.
     *
     * @param int|string $key
     *
     * @throws InvalidKeyException If the key is invalid.
     */
    protected function validateKey($key)
    {
        if (!is_string($key) && !is_int($key)) {
            throw InvalidKeyException::forKey($key);
        }
    }
}
