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
 * DocumentQueryRepositoryFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentQueryRepositoryFactory implements DocumentQueryRepositoryFactoryInterface
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
     * DocumentQueryRepositoryFactory constructor.
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
        return new DocumentQueryRepository(
            $this->documentManager->getRepository($documentName),
            $this->documentDatasourceFactory
        );
    }
}
