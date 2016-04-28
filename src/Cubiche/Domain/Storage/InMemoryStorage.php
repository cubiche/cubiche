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

use Cubiche\Core\Collections\ArrayCollection;
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
     */
    public function set($key, $value)
    {
        $this->validateKey($key);

        $this->store->set($key, $value);
    }

    /**
     * {@inheritdoc}
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
     */
    public function exists($key)
    {
        $this->validateKey($key);

        return $this->store->containsKey($key);
    }

    /**
     * {@inheritdoc}
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
     */
    public function clear()
    {
        $this->store->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return array_keys($this->store->toArray());
    }
}
