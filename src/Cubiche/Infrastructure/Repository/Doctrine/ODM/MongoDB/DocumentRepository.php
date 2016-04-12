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
use Cubiche\Domain\Collections\DataSource\IteratorDataSource;
use Cubiche\Domain\Collections\DataSourceCollection;
use Cubiche\Domain\Repository\Repository;
use Doctrine\ODM\MongoDB\DocumentRepository as MongoDBDocumentRepository;

/**
 * Document Repository Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentRepository extends Repository
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
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::add()
     */
    public function add($item)
    {
        $this->persist($item);
        $this->dm()->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::addAll()
     */
    public function addAll($items)
    {
        foreach ($items as $item) {
            $this->persist($item);
        }
        $this->dm()->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Repository\RepositoryInterface::update()
     */
    public function update($item)
    {
        $this->add($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::remove()
     */
    public function remove($item)
    {
        $this->dm()->remove($item);
        $this->dm()->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::clear()
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
     *
     * @see \Cubiche\Domain\Repository\RepositoryInterface::get()
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        return $this->repository->createQueryBuilder()->getQuery()->count(true);
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return $this->repository->createQueryBuilder()->getQuery()->getIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::slice()
     */
    public function slice($offset, $length = null)
    {
        $queryBuilder = $this->repository->createQueryBuilder();
        $queryBuilder->skip($offset);
        if ($length !== null) {
            $queryBuilder->limit($length);
        }

        return new DataSourceCollection(new IteratorDataSource($queryBuilder->getQuery()->getIterator()));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::find()
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceCollection($this->documentDataSource()->filteredDataSource($criteria));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::findOne()
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return $this->documentDataSource()->filteredDataSource($criteria)->findOne();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::toArray()
     */
    public function toArray()
    {
        return $this->repository->createQueryBuilder()->getQuery()->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::sorted()
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceCollection($this->documentDataSource()->sortedDataSource($criteria));
    }

    /**
     * @param mixed $item
     */
    protected function persist($item)
    {
        $this->checkType($item);
        $this->dm()->persist($item);
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
