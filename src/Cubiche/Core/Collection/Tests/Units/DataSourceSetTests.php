<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\Tests\Units;

use Cubiche\Core\Collection\DataSource\ArrayDataSource;
use Cubiche\Core\Collection\DataSourceSet;

/**
 * DataSourceSetTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DataSourceSetTests extends SetTestCase
{
    use DataSourceCollectionTestCase;

    /**
     * {@inheritdoc}
     */
    protected function randomCollection($size = null)
    {
        return new DataSourceSet(new ArrayDataSource($this->randomValues($size)));
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new DataSourceSet(new ArrayDataSource([]));
    }
}
