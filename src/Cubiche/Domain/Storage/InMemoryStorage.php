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

use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Storage\Exception\KeyNotFoundException;

/**
 * InMemoryStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryStorage extends AbstractStorage
{
    /**
     * @var ArrayCollection
     */
    protected $store;

    /**
     * Creates a new store.
     *
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->store = new ArrayCollection($elements);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::set()
     */
    public function set($key, $value)
    {
        $this->validateKey($key);

        $this->store->set($key, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::get()
     */
    public function get($key, $default = null)
    {
        $this->validateKey($key);
        if (!$this->store->containsKey($key)) {
            return $default;
        }

        return $this->store->get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::getOrFail()
     */
    public function getOrFail($key)
    {
        $this->validateKey($key);
        if (!$this->store->containsKey($key)) {
            throw KeyNotFoundException::forKey($key);
        }

        return $this->store->get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::getMultiple()
     */
    public function getMultiple(array $keys, $default = null)
    {
        $values = array();
        foreach ($keys as $key) {
            $values[$key] = $this->get($key, $default);
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::getMultipleOrFail()
     */
    public function getMultipleOrFail(array $keys)
    {
        $values = array();
        foreach ($keys as $key) {
            $values[$key] = $this->getOrFail($key);
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::exists()
     */
    public function exists($key)
    {
        $this->validateKey($key);

        return $this->store->containsKey($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::remove()
     */
    public function remove($key)
    {
        $this->validateKey($key);

        if ($this->store->containsKey($key)) {
            $this->store->remove(
                $this->store->get($key)
            );

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::clear()
     */
    public function clear()
    {
        $this->store->clear();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Storage\StorageInterface::keys()
     */
    public function keys()
    {
        return array_keys($this->store->toArray());
    }
}
