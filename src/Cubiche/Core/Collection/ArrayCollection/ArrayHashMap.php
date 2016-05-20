<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\ArrayCollection;

use Cubiche\Core\Collection\HashMapInterface;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * ArrayHashMap Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayHashMap extends ArrayCollection implements HashMapInterface
{
    /**
     * ArrayHashMap constructor.
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($this->containsKey($key)) {
            return $this->elements[$key];
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->validateKey($key);

        $this->elements[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key)
    {
        return $this->keys()->contains($key);
    }

    /**
     * {@inheritdoc}
     */
    public function containsValue($value)
    {
        return $this->values()->contains($value);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAt($key)
    {
        if ($this->containsKey($key)) {
            $removed = $this->elements[$key];
            unset($this->elements[$key]);

            return $removed;
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return new ArraySet(\array_keys($this->elements));
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return new ArrayList(\array_values($this->elements));
    }

    /**
     * {@inheritdoc}
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        uksort($this->items, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->removeAt($offset);
    }
}
