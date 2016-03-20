<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Collections\Persistence\Doctrine\Common\Collections;

use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Collections\ArrayCollectionInterface;
use Cubiche\Domain\Collections\DataSource\IteratorDataSource;
use Cubiche\Domain\Collections\DataSourceCollection;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\SpecificationInterface;

/**
 * Persistent Array Collection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PersistentArrayCollection extends PersistentCollectionAdapter implements ArrayCollectionInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::addAll()
     */
    public function addAll($items)
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::find()
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceCollection(new IteratorDataSource($this->getIterator(), $criteria));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::findOne()
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return (new IteratorDataSource($this->getIterator(), $criteria))->findOne();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::sorted()
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceCollection(new IteratorDataSource($this->getIterator(), null, $criteria));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::sort()
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        $items = $this->collection->toArray();
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        usort($items, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });

        $this->clear();
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::removeAt()
     */
    public function removeAt($key)
    {
        $this->remove($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::keys()
     */
    public function keys()
    {
        return new ArrayCollection($this->getKeys());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::values()
     */
    public function values()
    {
        return new ArrayCollection($this->getValues());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::slice()
     */
    public function slice($offset, $length = null)
    {
        return new ArrayCollection(parent::slice($offset, $length));
    }

    /**
     * {@inheritdoc}
     *
     * @see PersistentCollectionAdapter::contains()
     */
    public function contains($item)
    {
        return parent::contains($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see PersistentCollectionAdapter::containsKey()
     */
    public function containsKey($key)
    {
        return parent::containsKey($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see PersistentCollectionAdapter::get()
     */
    public function get($key)
    {
        return parent::get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see PersistentCollectionAdapter::set()
     */
    public function set($key, $value)
    {
        parent::set($key, $value);
    }
}