<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\LazyCollection;

use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Lazy Collection.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
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
    public function clear()
    {
        $this->lazyInitialize();

        $this->collection()->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $this->lazyInitialize();

        return $this->collection()->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $this->lazyInitialize();

        return $this->collection()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $this->lazyInitialize();

        return $this->collection()->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $this->lazyInitialize();

        return $this->collection()->count();
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        $this->lazyInitialize();

        return $this->collection()->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        $this->lazyInitialize();

        return $this->collection()->find($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        $this->lazyInitialize();

        return $this->collection()->sorted($criteria);
    }

    /**
     * Lazy initialize.
     */
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

    /**
     * Initialize the collection.
     */
    abstract protected function initialize();

    /**
     * @return CollectionInterface
     */
    protected function collection()
    {
        return $this->collection;
    }
}
