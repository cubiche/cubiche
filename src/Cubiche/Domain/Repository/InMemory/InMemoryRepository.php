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
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\Repository;

/**
 * InMemoryRepository Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class InMemoryRepository extends Repository
{
    /**
     * @var ArraySet
     */
    protected $collection;

    /**
     * @param string $entityName
     */
    public function __construct($entityName)
    {
        parent::__construct($entityName);

        $this->collection = new ArraySet();
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdInterface $id)
    {
        return $this->collection->findOne(Criteria::method('id')->eq($id));
    }

    /**
     * {@inheritdoc}
     */
    public function persist($element)
    {
        $this->checkType($element);
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
    public function remove($element)
    {
        $this->checkType($element);
        $this->collection->remove($element);
    }
}
