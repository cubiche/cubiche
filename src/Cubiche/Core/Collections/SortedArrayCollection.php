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

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * Sorted Array Collection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortedArrayCollection extends ArrayCollection
{
    /**
     * @var ComparatorInterface
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
     */
    public function add($item)
    {
        parent::add($item);

        $this->sort();
    }

    /**
     * {@inheritdoc}
     */
    public function addAll($items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }

        $this->sort();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($item)
    {
        parent::remove($item);

        $this->sort();
    }

    /**
     * {@inheritdoc}
     */
    public function removeAt($key)
    {
        parent::removeAt($key);

        $this->sort();
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        parent::set($key, $value);

        $this->sort();
    }

    /**
     * {@inheritdoc}
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
     */
    public function offsetUnset($offset)
    {
        parent::offsetUnset($offset);

        $this->sort();
    }
}
