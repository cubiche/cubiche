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

use Cubiche\Core\Collections\ArrayCollection\ArraySetInterface;
use Cubiche\Core\Collections\DataSourceSet;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Collections\DataSource\IteratorDataSource;

/**
 * PersistentArraySet Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PersistentArraySet extends PersistentCollectionAdapter implements ArraySetInterface
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
        return new DataSourceSet(new IteratorDataSource($this->getIterator(), $criteria));
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
        return new DataSourceSet(new IteratorDataSource($this->getIterator(), null, $criteria));
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
    public function removeAll($elements)
    {
        foreach ($elements as $element) {
            $this->removeElement($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function containsAll($elements)
    {
        foreach ($elements as $element) {
            if (!$this->contains($element)) {
                return false;
            }
        }

        return true;
    }
}
