<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Collections\Doctrine\Common\Collections;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\Collections\ArrayCollectionInterface;
use Cubiche\Core\Collections\DataSource\IteratorDataSource;
use Cubiche\Core\Collections\DataSourceCollection;

/**
 * Persistent Array Collection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PersistentArrayCollection extends PersistentCollectionAdapter implements ArrayCollectionInterface
{
    /**
     * {@inheritdoc}
     */
    public function addAll($items)
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceCollection(new IteratorDataSource($this->getIterator(), $criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return (new IteratorDataSource($this->getIterator(), $criteria))->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceCollection(new IteratorDataSource($this->getIterator(), null, $criteria));
    }

    /**
     * {@inheritdoc}
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
     */
    public function sortByKey(ComparatorInterface $criteria = null)
    {
        $items = $this->collection->toArray();
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        uksort($items, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });

        $this->clear();
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAt($key)
    {
        $this->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return new ArrayCollection($this->getKeys());
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return new ArrayCollection($this->getValues());
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return new ArrayCollection(parent::slice($offset, $length));
    }

    /**
     * {@inheritdoc}
     */
    public function contains($item)
    {
        return parent::contains($item);
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key)
    {
        return parent::containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return parent::get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        parent::set($key, $value);
    }
}
