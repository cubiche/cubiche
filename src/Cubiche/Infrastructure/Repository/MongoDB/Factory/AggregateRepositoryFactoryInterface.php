<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Repository\MongoDB\Factory;

use Cubiche\Infrastructure\Repository\MongoDB\AggregateRepository;

/**
 * AggregateRepositoryFactory interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface AggregateRepositoryFactoryInterface
{
    /**
     * @param string $documentName
     *
     * @return AggregateRepository
     */
    public function create($documentName);
}
