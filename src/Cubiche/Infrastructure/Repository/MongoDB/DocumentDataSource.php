<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Repository\MongoDB;

use Cubiche\Core\Collections\DataSource\DataSource;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Infrastructure\MongoDB\DocumentManager;
use Cubiche\Infrastructure\Repository\MongoDB\Visitor\ComparatorVisitorFactoryInterface;
use Cubiche\Infrastructure\Repository\MongoDB\Visitor\QueryBuilder;
use Cubiche\Infrastructure\Repository\MongoDB\Visitor\SpecificationVisitorFactoryInterface;

/**
 * DocumentDataSource class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentDataSource extends DataSource
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var string
     */
    protected $documentName;

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
     * DocumentDataSource constructor.
     *
     * @param DocumentManager                      $dm
     * @param string                               $documentName
     * @param SpecificationVisitorFactoryInterface $specificationVisitorFactory
     * @param ComparatorVisitorFactoryInterface    $comparatorVisitorFactory
     * @param SpecificationInterface|null          $searchCriteria
     * @param ComparatorInterface|null             $sortCriteria
     * @param int|null                             $offset
     * @param int|null                             $length
     */
    public function __construct(
        DocumentManager $dm,
        $documentName,
        SpecificationVisitorFactoryInterface $specificationVisitorFactory,
        ComparatorVisitorFactoryInterface $comparatorVisitorFactory,
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        parent::__construct($searchCriteria, $sortCriteria, $offset, $length);

        $this->dm = $dm;
        $this->documentName = $documentName;
        $this->specificationVisitorFactory = $specificationVisitorFactory;
        $this->comparatorVisitorFactory = $comparatorVisitorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->queryBuilder()->getQuery()->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    protected function calculateCount()
    {
        return $this->queryBuilder()->getQuery()->count();
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
     * @return QueryBuilder
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
            $this->documentName,
            $this->specificationVisitorFactory,
            $this->comparatorVisitorFactory,
            $searchCriteria,
            $sortCriteria,
            $offset,
            $length
        );
    }
}
