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

use Cubiche\Core\Collection\ArrayCollection\SortedArraySet;

/**
 * DataSourceSorted Set.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DataSourceSortedSet extends DataSourceSet
{
    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        $this->collection = new SortedArraySet();

        $this->collection->addAll($this->dataSource->getIterator());
    }
}
