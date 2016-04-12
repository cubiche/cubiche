<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB;

/**
 * Document Data Source Factory Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface DocumentDataSourceFactoryInterface
{
    /**
     * @param string $documentName
     *
     * @return DocumentDataSource
     */
    public function create($documentName = null);
}
