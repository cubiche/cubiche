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

use Cubiche\Core\Collection\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collection\LazyCollection\LazyHashMap;

/**
 * Data Source HashMap.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DataSourceHashMap extends LazyHashMap
{
    use DataSourceCollectionTrait;

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        $this->collection = new ArrayHashMap();

        foreach ($this->dataSource->getIterator() as $key => $value) {
            $this->collection->set($key, $value);
        }
    }
}
