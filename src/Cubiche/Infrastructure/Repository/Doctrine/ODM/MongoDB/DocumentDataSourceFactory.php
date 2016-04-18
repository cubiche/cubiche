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

use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query\ComparatorVisitorFactoryInterface;
use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query\SpecificationVisitorFactoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Document Data Source Factory Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
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

    public function create($documentName = null)
    {
        return new DocumentDataSource(
            $this->dm,
            $this->specificationVisitorFactory,
            $this->comparatorVisitorFactory,
            $documentName
        );
    }
}
