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

use Cubiche\Core\Collections\ArrayCollection;

/**
 * InMemoryStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryStorage extends AbstractStorage implements StorageInterface
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
    public function has($key)
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
