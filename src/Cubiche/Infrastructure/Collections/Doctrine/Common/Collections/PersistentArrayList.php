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

use Cubiche\Core\Collections\ArrayCollection\ArrayListInterface;
use Cubiche\Core\Collections\DataSourceList;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Collections\DataSource\IteratorDataSource;

/**
 * PersistentArrayList Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PersistentArrayList extends PersistentCollectionAdapter implements ArrayListInterface
{
    /**
     * {@inheritdoc}
     */
    public function addAll($elements)
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceList(new IteratorDataSource($this->getIterator(), $criteria));
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
        return new DataSourceList(new IteratorDataSource($this->getIterator(), null, $criteria));
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

        usort($elements, function ($a, $b) use ($criteria) {
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
    public function removeAt($key)
    {
        $this->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAll($elements)
    {
        foreach ($elements as $element) {
            $this->removeElement($element);
        }
    }
}
