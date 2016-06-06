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

use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Collections\LazyCollection\LazyList;
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
