<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Comparable\ComparatorInterface;

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
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::add()
     */
    public function add($item)
    {
        $this->lazyInitialize();

        return $this->collection->add($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::remove()
     */
    public function remove($item)
    {
        $this->lazyInitialize();

        return $this->collection->remove($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::clear()
     */
    public function clear()
    {
        $this->lazyInitialize();

        return $this->collection->clear();
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        $this->lazyInitialize();

        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        $this->lazyInitialize();

        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::slice()
     */
    public function slice($offset, $length = null)
    {
        $this->lazyInitialize();

        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::find()
     */
    public function find(SpecificationInterface $criteria)
    {
        $this->lazyInitialize();

        return $this->collection->find($criteria);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::findOne()
     */
    public function findOne(SpecificationInterface $criteria)
    {
        $this->lazyInitialize();

        return $this->collection->findOne($criteria);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::toArray()
     */
    public function toArray()
    {
        $this->lazyInitialize();

        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::sorted()
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
