<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Collections\Tests\Asserters;

use Cubiche\Domain\Collections\DataSource\DataSourceInterface;
use Cubiche\Domain\Collections\CollectionInterface;

/**
 * Asserters trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Asserters
{
    /**
     * @param CollectionInterface
     *
     * @return \Cubiche\Domain\Collections\Tests\Asserters\CollectionAsserter
     */
    public function collection($collection)
    {
        return static::__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param DataSourceInterface
     *
     * @return \Cubiche\Domain\Collections\Tests\Asserters\DataSourceAsserter
     */
    public function datasource($datasource)
    {
        return static::__call(__FUNCTION__, func_get_args());
    }
}
