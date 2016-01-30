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

use Cubiche\Domain\Storage\Exception\KeyNotFoundException;

/**
 * NoneStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NoneStorage extends AbstractStorage
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::set()
     */
    public function set($key, $value)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::get()
     */
    public function get($key, $default = null)
    {
        return $default;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::getOrFail()
     */
    public function getOrFail($key)
    {
        throw KeyNotFoundException::forKey($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::getMultiple()
     */
    public function getMultiple(array $keys, $default = null)
    {
        return array_fill_keys($keys, $default);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::getMultipleOrFail()
     */
    public function getMultipleOrFail(array $keys)
    {
        throw KeyNotFoundException::forKeys($keys);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::exists()
     */
    public function exists($key)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::remove()
     */
    public function remove($key)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::clear()
     */
    public function clear()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::keys()
     */
    public function keys()
    {
        return array();
    }
}
