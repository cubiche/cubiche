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

use Cubiche\Core\Collections\DataSource\IteratorDataSource;
use Cubiche\Core\Collections\DataSourceSet;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Model\ReadModelInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Infrastructure\MongoDB\Repository;
use Cubiche\Infrastructure\Repository\MongoDB\Factory\DocumentDataSourceFactoryInterface;

/**
 * DocumentQueryRepository class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentQueryRepository implements QueryRepositoryInterface
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var DocumentDataSourceFactoryInterface
     */
    protected $documentDataSourceFactory;

    /**
     * @var DocumentDataSource
     */
    protected $documentDataSource = null;

    /**
     * DocumentQueryRepository constructor.
     *
     * @param Repository                         $repository
     * @param DocumentDataSourceFactoryInterface $documentDataSourceFactory
     */
    public function __construct(
        Repository $repository,
        DocumentDataSourceFactoryInterface $documentDataSourceFactory
    ) {
        $this->repository = $repository;
        $this->documentDataSourceFactory = $documentDataSourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->repository
            ->createQueryBuilder()
            ->getQuery()
            ->remove()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->repository
            ->createQueryBuilder()
            ->getQuery()
            ->toArray()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->repository
            ->createQueryBuilder()
            ->getQuery()
            ->getIterator()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceSet($this->documentDataSource()->sortedDataSource($criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        $queryBuilder = $this->repository->createQueryBuilder();
        $queryBuilder->skip($offset);

        if ($length !== null) {
            $queryBuilder->limit($length);
        }

        return new DataSourceSet(new IteratorDataSource($queryBuilder->getQuery()->getIterator()));
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceSet($this->documentDataSource()->filteredDataSource($criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return $this->documentDataSource()->filteredDataSource($criteria)->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdInterface $id)
    {
        return $this->repository->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function persist(ReadModelInterface $element)
    {
        $this->repository->persist($element);
    }

    /**
     * {@inheritdoc}
     */
    public function persistAll($elements)
    {
        $this->repository->persistAll($elements);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ReadModelInterface $element)
    {
        $this->repository->remove($element);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->repository
            ->createQueryBuilder()
            ->getQuery()
            ->count()
        ;
    }

    /**
     * @return DocumentDataSource
     */
    protected function documentDataSource()
    {
        if ($this->documentDataSource === null) {
            $this->documentDataSource = $this->documentDataSourceFactory->create($this->repository->documentName());
        }

        return $this->documentDataSource;
    }
}
