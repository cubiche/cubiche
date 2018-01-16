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

use Cubiche\Infrastructure\MongoDB\DocumentManager;
use Cubiche\Infrastructure\Repository\MongoDB\DocumentDataSource;
use Cubiche\Infrastructure\Repository\MongoDB\Visitor\ComparatorVisitorFactoryInterface;
use Cubiche\Infrastructure\Repository\MongoDB\Visitor\SpecificationVisitorFactoryInterface;

/**
 * DocumentDataSourceFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentDataSourceFactory implements DocumentDataSourceFactoryInterface
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var SpecificationVisitorFactoryInterface
     */
    protected $specificationVisitorFactory;

    /**
     * @var ComparatorVisitorFactoryInterface
     */
    protected $comparatorVisitorFactory;

    /**
     * DocumentDataSourceFactory constructor.
     *
     * @param DocumentManager                      $dm
     * @param SpecificationVisitorFactoryInterface $specificationVisitorFactory
     * @param ComparatorVisitorFactoryInterface    $comparatorVisitorFactory
     */
    public function __construct(
        DocumentManager $dm,
        SpecificationVisitorFactoryInterface $specificationVisitorFactory,
        ComparatorVisitorFactoryInterface $comparatorVisitorFactory
    ) {
        $this->dm = $dm;
        $this->specificationVisitorFactory = $specificationVisitorFactory;
        $this->comparatorVisitorFactory = $comparatorVisitorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create($documentName)
    {
        return new DocumentDataSource(
            $this->dm,
            $documentName,
            $this->specificationVisitorFactory,
            $this->comparatorVisitorFactory
        );
    }
}
