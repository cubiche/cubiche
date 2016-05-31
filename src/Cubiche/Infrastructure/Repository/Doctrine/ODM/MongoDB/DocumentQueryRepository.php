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

use Cubiche\Core\Collection\DataSource\IteratorDataSource;
use Cubiche\Core\Collection\DataSourceSet;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\QueryRepository;
use Doctrine\ODM\MongoDB\DocumentRepository as MongoDBDocumentRepository;

/**
 * DocumentQueryRepository Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentQueryRepository extends QueryRepository
{
    /**
     * @var MongoDBDocumentRepository
     */
    protected $repository;

    /**
     * @var DocumentDataSourceFactoryInterface
     */
    protected $documentDataSourceFactory;

    /**
     * @var DocumentDataSource
     */
    private $documentDataSource = null;

    /**
     * @param MongoDBDocumentRepository          $repository
     * @param DocumentDataSourceFactoryInterface $documentDataSourceFactory
     */
    public function __construct(
        MongoDBDocumentRepository $repository,
        DocumentDataSourceFactoryInterface $documentDataSourceFactory
    ) {
        parent::__construct($repository->getDocumentName());

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
            ->remove()
            ->getQuery()
            ->execute()
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
        return $this->repository->createQueryBuilder()->getQuery()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->repository->createQueryBuilder()->getQuery()->getIterator();
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
        return $this->repository->find($id->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function persist($element)
    {
        $this->checkType($element);
        $this->dm()->persist($element);
    }

    /**
     * {@inheritdoc}
     */
    public function persistAll($elements)
    {
        foreach ($elements as $element) {
            $this->persist($element);
        }

        $this->dm()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($element)
    {
        $this->checkType($element);

        $this->dm()->remove($element);
        $this->dm()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->repository->createQueryBuilder()->getQuery()->count(true);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function dm()
    {
        return $this->repository->getDocumentManager();
    }

    /**
     * @return \Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\DocumentDataSource
     */
    protected function documentDataSource()
    {
        if ($this->documentDataSource === null) {
            $this->documentDataSource = $this->documentDataSourceFactory->create($this->repository->getDocumentName());
        }

        return $this->documentDataSource;
    }
}
