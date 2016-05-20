<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection;

use Cubiche\Core\Collection\ArrayCollection\ArrayList;
use Cubiche\Core\Collection\LazyCollection\LazyList;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Data Source List.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DataSourceList extends LazyList
{
    use DataSourceCollectionTrait;

    /**
     * {@inheritdoc}
     */
    public function subList($offset, $length = null)
    {
        if ($this->isInitialized()) {
            return parent::subList($offset, $length);
        }

        return new self($this->dataSource->slicedDataSource($offset, $length));
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        if ($this->isInitialized()) {
            return parent::find($criteria);
        }

        return new self($this->dataSource->filteredDataSource($criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        if ($this->isInitialized()) {
            return parent::findOne($criteria);
        }

        return $this->dataSource->filteredDataSource($criteria)->findOne();
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        $this->collection = new ArrayList();

        $this->collection->addAll($this->dataSource->getIterator());
    }
}
