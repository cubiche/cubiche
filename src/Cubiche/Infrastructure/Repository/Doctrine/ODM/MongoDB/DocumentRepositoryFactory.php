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

use Doctrine\ODM\MongoDB\DocumentManager;

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
    protected $documentManager;

    /**
     * @var DocumentDataSourceFactory
     */
    protected $documentDatasourceFactory;

    /**
     * DocumentRepositoryFactory constructor.
     *
     * @param DocumentManager           $documentManager
     * @param DocumentDataSourceFactory $documentDatasourceFactory
     */
    public function __construct(DocumentManager $documentManager, DocumentDataSourceFactory $documentDatasourceFactory)
    {
        $this->documentManager = $documentManager;
        $this->documentDatasourceFactory = $documentDatasourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create($documentName)
    {
        return new DocumentRepository(
            $this->documentManager->getRepository($documentName),
            $this->documentDatasourceFactory
        );
    }
}
