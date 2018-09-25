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

use Cubiche\Domain\Repository\InMemory\InMemoryRepository;

/**
 * InMemoryRepositoryFactory Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($modelName)
    {
        return new InMemoryRepository();
    }
}
