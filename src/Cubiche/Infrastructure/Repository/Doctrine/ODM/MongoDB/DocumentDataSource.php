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

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Collections\DataSource\DataSource;
use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query\QueryBuilder;
use Doctrine\ODM\MongoDB\DocumentManager;
use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query\SpecificationVisitorFactoryInterface;
use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query\ComparatorVisitorFactoryInterface;

/**
 * Document Data Source Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentDataSource extends DataSource
{
    /**
     * @var string
     */
    protected $documentName;

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
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @param DocumentManager                      $dm
     * @param SpecificationVisitorFactoryInterface $specificationVisitorFactory
     * @param ComparatorVisitorFactoryInterface    $comparatorVisitorFactory
     * @param string                               $documentName
     * @param SpecificationInterface               $searchCriteria
     * @param ComparatorInterface                  $sortCriteria
     * @param string                               $offset
     * @param string                               $length
     */
    public function __construct(
        DocumentManager $dm,
        SpecificationVisitorFactoryInterface $specificationVisitorFactory,
        ComparatorVisitorFactoryInterface $comparatorVisitorFactory,
        $documentName = null,
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        parent::__construct($searchCriteria, $sortCriteria, $offset, $length);

        $this->dm = $dm;
        $this->specificationVisitorFactory = $specificationVisitorFactory;
        $this->comparatorVisitorFactory = $comparatorVisitorFactory;
        $this->documentName = $documentName;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->queryBuilder()->getQuery()->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function findOne()
    {
        return $this->queryBuilder()->getQuery()->getSingleResult();
    }

    /**
     * {@inheritdoc}
     */
    public function filteredDataSource(SpecificationInterface $criteria)
    {
        if ($this->isFiltered()) {
            $criteria = $this->searchCriteria()->andX($criteria);
        }

        return $this->createDocumentDataSource(
            $criteria,
            $this->sortCriteria(),
            $this->offset(),
            $this->length()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function slicedDataSource($offset, $length = null)
    {
        return $this->createDocumentDataSource(
            $this->searchCriteria(),
            $this->sortCriteria(),
            $this->actualOffset($offset),
            $this->actualLength($offset, $length)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sortedDataSource(ComparatorInterface $sortCriteria)
    {
        return $this->createDocumentDataSource(
            $this->searchCriteria(),
            $sortCriteria,
            $this->offset(),
            $this->length()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function calculateCount()
    {
        return $this->queryBuilder()->getQuery()->count(true);
    }

    /**
     * @return \Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function queryBuilder()
    {
        if ($this->queryBuilder === null) {
            $this->queryBuilder = new QueryBuilder(
                $this->dm,
                $this->documentName,
                $this->specificationVisitorFactory,
                $this->comparatorVisitorFactory
            );
            if ($this->isFiltered()) {
                $this->queryBuilder->addSearchCriteria($this->searchCriteria());
            }
            if ($this->offset !== null) {
                $this->queryBuilder->skip($this->offset);
            }
            if ($this->length !== null) {
                $this->queryBuilder->limit($this->length);
            }
            if ($this->isSorted()) {
                $this->queryBuilder->addSortCriteria($this->sortCriteria());
            }
        }

        return $this->queryBuilder;
    }

    /**
     * @param SpecificationInterface $searchCriteria
     * @param ComparatorInterface    $sortCriteria
     * @param string                 $offset
     * @param string                 $length
     *
     * @return DocumentDataSource
     */
    protected function createDocumentDataSource(
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        return new self(
            $this->dm,
            $this->specificationVisitorFactory,
            $this->comparatorVisitorFactory,
            $this->documentName,
            $searchCriteria,
            $sortCriteria,
            $offset,
            $length
        );
    }
}
