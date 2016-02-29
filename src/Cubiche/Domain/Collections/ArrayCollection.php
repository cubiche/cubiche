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

use Cubiche\Domain\Collections\DataSource\ArrayDataSource;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\SpecificationInterface;

/**
 * Array Collection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayCollection implements ArrayCollectionInterface
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @param array $items
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::add()
     */
    public function add($item)
    {
        $this->items[] = $item;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::remove()
     */
    public function remove($item)
    {
        $key = \array_search($item, $this->items, true);
        if ($key !== false) {
            unset($this->items[$key]);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::clear()
     */
    public function clear()
    {
        $this->items = array();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::contains()
     */
    public function contains($item)
    {
        return \in_array($item, $this->items, true);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::exists()
     */
    public function exists($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::get()
     */
    public function get($key)
    {
        return isset($this->items[$key]) ? $this->items[$key] : null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::set()
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        return \count($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::slice()
     */
    public function slice($offset, $length = null)
    {
        return new static(\array_slice($this->items, $offset, $length, true));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::find()
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceCollection(new ArrayDataSource($this->items, $criteria));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::findOne()
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return (new ArrayDataSource($this->items, $criteria))->findOne();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::toArray()
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::sort()
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        usort($this->items, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::sorted()
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceCollection(new ArrayDataSource($this->items, null, $criteria));
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }

        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}
