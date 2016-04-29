<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Collections;

use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * Lazy Collection.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class LazyCollection implements CollectionInterface
{
    /**
     * @var CollectionInterface
     */
    protected $collection;

    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * {@inheritdoc}
     */
    public function add($item)
    {
        $this->lazyInitialize();

        return $this->collection->add($item);
    }

    /**
     * {@inheritdoc}
     */
    public function addAll($items)
    {
        $this->lazyInitialize();

        return $this->collection->addAll($items);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($item)
    {
        $this->lazyInitialize();

        return $this->collection->remove($item);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->lazyInitialize();

        return $this->collection->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $this->lazyInitialize();

        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $this->lazyInitialize();

        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        $this->lazyInitialize();

        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        $this->lazyInitialize();

        return $this->collection->find($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        $this->lazyInitialize();

        return $this->collection->findOne($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $this->lazyInitialize();

        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        $this->lazyInitialize();

        return $this->collection->sorted($criteria);
    }

    protected function lazyInitialize()
    {
        if (!$this->isInitialized()) {
            $this->initialize();
            $this->initialized = true;
        }
    }

    /**
     * @return bool
     */
    protected function isInitialized()
    {
        return $this->initialized;
    }

    abstract protected function initialize();
}
