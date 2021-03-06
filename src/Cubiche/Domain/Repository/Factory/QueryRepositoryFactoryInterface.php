<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Repository\Factory;

use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * QueryRepositoryFactory interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface QueryRepositoryFactoryInterface
{
    /**
     * Create a new query repository instance.
     *
     * @param string $modelName
     *
     * @return QueryRepositoryInterface
     */
    public function create($modelName);
}
