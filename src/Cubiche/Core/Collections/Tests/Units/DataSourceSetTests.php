<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\Tests\Units;

use Cubiche\Core\Collections\DataSource\ArrayDataSource;
use Cubiche\Core\Collections\DataSourceSet;

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
