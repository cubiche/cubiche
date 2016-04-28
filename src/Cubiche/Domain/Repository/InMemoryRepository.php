<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Repository;

use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * In Memory Repository Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class InMemoryRepository extends Repository
{
    /**
     * @var ArrayCollection
     */
    protected $collection;

    /**
     * @param string $entityName
     */
    public function __construct($entityName)
    {
        parent::__construct($entityName);

        $this->collection = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function add($item)
    {
        $this->checkType($item);
        $this->collection->add($item);
    }

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
    public function update($item)
    {
        $this->checkType($item);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($item)
    {
        $this->checkType($item);
        $this->collection->remove($item);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->collection->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->collection->findOne(Criteria::method('id')->eq($id));
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        return $this->collection->find($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return $this->collection->findOne($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return $this->collection->sorted($criteria);
    }
}
