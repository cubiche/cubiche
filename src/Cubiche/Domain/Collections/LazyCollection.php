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

    protected function lazyInitialize()
    {
        if (!$this->isInitialized()) {
            $this->initialize();
            $this->initialized = true;
        }
    }

    abstract protected function initialize();

    /**
     * @return bool
     */
    protected function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::add()
     */
    public function add($item)
    {
        $this->initialize();

        return $this->collection->add($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::remove()
     */
    public function remove($item)
    {
        $this->initialize();

        return $this->collection->remove($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::clear()
     */
    public function clear()
    {
        $this->initialize();

        return $this->collection->clear();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::contains()
     */
    public function contains($item)
    {
        $this->initialize();

        return $this->collection->contains($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::exists()
     */
    public function exists($key)
    {
        $this->initialize();

        return $this->collection->exists($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::get()
     */
    public function get($key)
    {
        $this->initialize();

        return $this->collection->get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        $this->initialize();

        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        $this->initialize();

        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::slice()
     */
    public function slice($offset, $length = null)
    {
        $this->initialize();

        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::find()
     */
    public function find(SpecificationInterface $specification)
    {
        $this->initialize();

        return $this->collection->find($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::toArray()
     */
    public function toArray()
    {
        $this->initialize();

        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::sorted()
     */
    public function sorted(ComparatorInterface $comparator)
    {
        $this->initialize();

        return $this->collection->sorted($comparator);
    }
}
