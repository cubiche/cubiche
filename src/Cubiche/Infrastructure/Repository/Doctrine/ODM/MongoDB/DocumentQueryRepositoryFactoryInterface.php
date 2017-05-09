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
 * DocumentQueryRepositoryFactory interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface DocumentQueryRepositoryFactoryInterface
{
    /**
     * @param string $documentName
     *
     * @return DocumentQueryRepository
     */
    public function create($documentName);
}
