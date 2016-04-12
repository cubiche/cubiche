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
use Cubiche\Domain\Collections\DataSource\DataSource;
use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query\QueryBuilder;
use Doctrine\ODM\MongoDB\DocumentRepository as MongoDBDocumentRepository;

/**
 * Document Data Source Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentDataSource extends DataSource
{
    /**
     * @var MongoDBDocumentRepository
     */
    protected $repository;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @param MongoDBDocumentRepository $repository
     * @param SpecificationInterface    $searchCriteria
     * @param ComparatorInterface       $sortCriteria
     * @param int                       $offset
     * @param int                       $length
     */
    public function __construct(
        MongoDBDocumentRepository $repository,
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        parent::__construct($searchCriteria, $sortCriteria, $offset, $length);

        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return $this->queryBuilder()->getQuery()->execute();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::findOne()
     */
    public function findOne()
    {
        return $this->queryBuilder()->getQuery()->getSingleResult();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::filteredDataSource()
     */
    public function filteredDataSource(SpecificationInterface $criteria)
    {
        if ($this->isFiltered()) {
            $criteria = $this->searchCriteria()->andX($criteria);
        }

        return new self(
            $this->repository,
            $criteria,
            $this->sortCriteria(),
            $this->offset(),
            $this->length()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::slicedDataSource()
     */
    public function slicedDataSource($offset, $length = null)
    {
        return new self(
            $this->repository,
            $this->searchCriteria(),
            $this->sortCriteria(),
            $this->actualOffset($offset),
            $this->actualLength($offset, $length)
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::sortedDataSource()
     */
    public function sortedDataSource(ComparatorInterface $sortCriteria)
    {
        return new self(
            $this->repository,
            $this->searchCriteria(),
            $sortCriteria,
            $this->offset(),
            $this->length()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSource::calculateCount()
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
                $this->repository->getDocumentManager(),
                $this->repository->getDocumentName()
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
}
