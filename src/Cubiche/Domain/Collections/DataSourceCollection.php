<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

use Cubiche\Domain\Collections\DataSource\DataSourceInterface;
use Cubiche\Domain\Comparable\ComparatorInterface;

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
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::initialize()
     */
    protected function initialize()
    {
        $this->collection = new ArrayCollection();

        foreach ($this->dataSource->getIterator() as $item) {
            $this->collection->add($item);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::count()
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
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::getIterator()
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
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::slice()
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
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::sorted()
     */
    public function sorted(ComparatorInterface $comparator)
    {
        if ($this->isInitialized()) {
            return parent::sorted($comparator);
        }

        return new self($this->dataSource->sortedDataSource($comparator));
    }
}
