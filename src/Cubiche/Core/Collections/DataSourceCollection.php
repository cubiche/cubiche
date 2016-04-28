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

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Collections\DataSource\DataSourceInterface;

/**
 * Data Source Collection.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DataSourceCollection extends LazyCollection
{
    /**
     * @var DataSourceInterface
     */
    protected $dataSource;

    /**
     * @param DataSourceInterface $dataSource
     */
    public function __construct(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->isInitialized()) {
            return parent::count();
        }

        return $this->dataSource->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if ($this->isInitialized()) {
            return parent::getIterator();
        }

        return $this->dataSource->getIterator();
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
    public function slice($offset, $length = null)
    {
        if ($this->isInitialized()) {
            return parent::slice($offset, $length);
        }

        return new self($this->dataSource->slicedDataSource($offset, $length));
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        if ($this->isInitialized()) {
            return parent::sorted($criteria);
        }

        return new self($this->dataSource->sortedDataSource($criteria));
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        $this->collection = new ArrayCollection();

        foreach ($this->dataSource->getIterator() as $item) {
            $this->collection->add($item);
        }
    }
}
