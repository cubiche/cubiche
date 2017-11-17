<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Repository\InMemory;

use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Model\ReadModelInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * InMemoryQueryRepository Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class InMemoryQueryRepository implements QueryRepositoryInterface
{
    /**
     * @var ArraySet
     */
    protected $collection;

    /**
     * InMemoryQueryRepository constructor.
     */
    public function __construct()
    {
        $this->collection = new ArraySet();
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
    public function isEmpty()
    {
        return $this->collection->isEmpty();
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
    public function get(IdInterface $id)
    {
        return $this->findOne(Criteria::method('id')->eq($id));
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
    public function getIterator()
    {
        return $this->collection->getIterator();
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
    public function persist(ReadModelInterface $element)
    {
        $this->collection->add($element);
    }

    /**
     * {@inheritdoc}
     */
    public function persistAll($elements)
    {
        foreach ($elements as $element) {
            $this->persist($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ReadModelInterface $element)
    {
        $this->collection->remove($element);
    }
}
