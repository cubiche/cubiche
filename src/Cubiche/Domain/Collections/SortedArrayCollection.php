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

use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\Criteria;

/**
 * Sorted Array Collection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortedArrayCollection extends ArrayCollection
{
    /**
     * @var array
     */
    protected $criteria;

    /**
     * SortedArrayCollection constructor.
     *
     * @param array                    $items
     * @param ComparatorInterface|null $criteria
     */
    public function __construct(array $items = array(), ComparatorInterface $criteria = null)
    {
        parent::__construct($items);

        if ($criteria === null) {
            $criteria = new Comparator();
        }

        $this->sort($criteria);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::add()
     */
    public function add($item)
    {
        parent::add($item);

        $this->sort();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::addAll()
     */
    public function addAll($items)
    {
        foreach ($items as $item) {
            parent::add($item);
        }

        $this->sort();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::remove()
     */
    public function remove($item)
    {
        parent::remove($item);

        $this->sort();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::removeAt()
     */
    public function removeAt($key)
    {
        parent::removeAt($key);

        $this->sort();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::set()
     */
    public function set($key, $value)
    {
        parent::set($key, $value);

        $this->sort();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\ArrayCollectionInterface::sort()
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        if ($criteria !== null) {
            $this->criteria = $criteria;
        }

        parent::sort($this->criteria);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        parent::offsetUnset($offset);

        $this->sort();
    }
}
