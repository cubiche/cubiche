<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Factory;

use Cubiche\Infrastructure\MongoDB\DocumentDataSource;

/**
 * DocumentDataSourceFactory interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface DocumentDataSourceFactoryInterface
{
    /**
     * @param string $documentName
     *
     * @return DocumentDataSource
     */
    public function create($documentName);
}
