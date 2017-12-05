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

use Cubiche\Infrastructure\MongoDB\Common\DocumentManager;
use Cubiche\Infrastructure\MongoDB\Common\Repository;
use Cubiche\Infrastructure\MongoDB\DocumentRepository;

/**
 * DocumentRepositoryFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentRepositoryFactory implements DocumentRepositoryFactoryInterface
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * DocumentRepositoryFactory constructor.
     *
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * {@inheritdoc}
     */
    public function create($documentName)
    {
        return new DocumentRepository(
            new Repository($this->dm, $documentName)
        );
    }
}
