<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units;

use Cubiche\Domain\Collections\DataSource\ArrayDataSource;
use Cubiche\Domain\Collections\DataSourceCollection;

/**
 * DataSourceCollectionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DataSourceCollectionTests extends LazyCollectionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createCollection(array $items = array())
    {
        return new DataSourceCollection(new ArrayDataSource($items));
    }
}
