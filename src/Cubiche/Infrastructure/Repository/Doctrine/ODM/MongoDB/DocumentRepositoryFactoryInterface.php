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
 * DocumentRepositoryFactory interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface DocumentRepositoryFactoryInterface
{
    /**
     * @param string $documentName
     *
     * @return DocumentRepository
     */
    public function create($documentName);
}
