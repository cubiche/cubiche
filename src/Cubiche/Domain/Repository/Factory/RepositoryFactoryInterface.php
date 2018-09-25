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

use Cubiche\Domain\Repository\RepositoryInterface;

/**
 * RepositoryFactory interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface RepositoryFactoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param string $aggregateName
     *
     * @return RepositoryInterface
     */
    public function create($aggregateName);
}
