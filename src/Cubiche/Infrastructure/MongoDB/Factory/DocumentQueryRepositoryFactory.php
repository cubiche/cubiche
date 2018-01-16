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
use Cubiche\Infrastructure\MongoDB\DocumentQueryRepository;

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
    protected $dm;

    /**
     * @var DocumentDataSourceFactoryInterface
     */
    protected $documentDataSourceFactory;

    /**
     * DocumentQueryRepositoryFactory constructor.
     *
     * @param DocumentManager                    $dm
     * @param DocumentDataSourceFactoryInterface $documentDataSourceFactory
     */
    public function __construct(
        DocumentManager $dm,
        DocumentDataSourceFactoryInterface $documentDataSourceFactory
    ) {
        $this->dm = $dm;
        $this->documentDataSourceFactory = $documentDataSourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create($documentName)
    {
        return new DocumentQueryRepository(
            new Repository($this->dm, $documentName),
            $this->documentDataSourceFactory
        );
    }
}
