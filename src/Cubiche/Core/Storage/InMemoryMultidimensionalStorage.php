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
 * InMemoryMultidimensionalStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryMultidimensionalStorage extends AbstractStorage implements MultidimensionalStorageInterface
{
    /**
     * @var ArrayCollection
     */
    protected $store;

    /**
     * InMemoryMultidimensionalStorage constructor.
     */
    public function __construct()
    {
        $this->store = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function has($key)
    {
        $this->validateKey($key);

        return $this->store->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function push($key, $value)
    {
        if (!$this->has($key)) {
            $this->store->set($key, new ArrayCollection());
        }

        /** @var ArrayCollection $collection */
        $collection = $this->store->get($key);
        $collection->add($value);
    }

    /**
     * {@inheritdoc}
     */
    public function pop($key)
    {
        if (!$this->has($key)) {
            return;
        }

        /** @var ArrayCollection $collection */
        $collection = $this->store->get($key);

        // get the last element and remove from the collection
        $index = $collection->count() - 1;
        $sliced = $collection->slice($index, 1);
        $collection->removeAt($index);

        // if the collection is empty then remove the key
        if ($collection->count() == 0) {
            $this->store->removeAt($key);
        }

        return $sliced->get($index);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll($key)
    {
        if (!$this->has($key)) {
            return array();
        }

        /** @var ArrayCollection $collection */
        $collection = $this->store->get($key);

        return $collection->toArray();
    }
}
