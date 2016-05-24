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

use Cubiche\Core\Collection\CollectionInterface;
use Cubiche\Core\Collection\DataSource\ArrayDataSource;
use Cubiche\Core\Collection\DataSourceHashMap;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * ArrayHashMap Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayHashMap extends ArrayCollection implements ArrayHashMapInterface
{
    /**
     * ArrayHashMap constructor.
     *
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        foreach ($elements as $key => $element) {
            $this->set($key, $element);
        }
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
        return $this->hasKey($key);
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
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceHashMap(new ArrayDataSource($this->elements, $criteria));
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

        uksort($this->elements, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });
    }

    /**
     * @param ComparatorInterface $criteria
     *
     * @return CollectionInterface
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceHashMap(new ArrayDataSource($this->elements, null, $criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->removeAt($offset);
    }
}
