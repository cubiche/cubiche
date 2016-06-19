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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMapInterface;
use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Core\Collections\DataSource\IteratorDataSource;
use Cubiche\Core\Collections\DataSourceHashMap;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * PersistentArrayHashMap Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PersistentArrayHashMap extends PersistentCollectionAdapter implements ArrayHashMapInterface
{
    /**
     * {@inheritdoc}
     */
    public function containsValue($value)
    {
        return $this->contains($value);
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return new ArraySet($this->getKeys());
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return new ArrayList($this->getValues());
    }

    /**
     * {@inheritdoc}
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        $elements = $this->collection->toArray();
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        uksort($elements, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });

        $this->clear();
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceHashMap(new IteratorDataSource($this->getIterator(), null, $criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceHashMap(new IteratorDataSource($this->getIterator(), $criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function removeAt($key)
    {
        $this->remove($key);
    }
}
