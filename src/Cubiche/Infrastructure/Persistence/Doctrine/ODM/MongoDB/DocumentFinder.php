<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB;

use Cubiche\Domain\Collections\Finder;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\SpecificationInterface;
use Doctrine\ODM\MongoDB\DocumentRepository as MongoDBDocumentRepository;

/**
 * Document Finder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentFinder extends Finder
{
    /**
     * @var MongoDBDocumentRepository
     */
    protected $repository;

    /**
     * @var SpecificationQueryBuilder
     */
    protected $queryBuilder;

    /**
     * @param MongoDBDocumentRepository $repository
     * @param SpecificationInterface    $specification
     * @param ComparatorInterface       $comparator
     * @param int                       $offset
     * @param int                       $length
     */
    public function __construct(
        MongoDBDocumentRepository $repository,
        SpecificationInterface $specification = null,
        ComparatorInterface $comparator = null,
        $offset = null,
        $length = null
    ) {
        parent::__construct($specification, $comparator, $offset, $length);
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
     * @see \Cubiche\Domain\Collections\FinderInterface::sliceFinder()
     */
    public function sliceFinder($offset, $length = null)
    {
        return new self($this->repository, $this->specification(), $this->comparator(), $offset, $length);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\FinderInterface::sortedFinder()
     */
    public function sortedFinder(ComparatorInterface $comparator)
    {
        return new self(
            $this->repository,
            $this->specification(),
            $this->comparator(),
            $this->offset(),
            $this->length()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Finder::calculateCount()
     */
    protected function calculateCount()
    {
        return $this->queryBuilder()->getQuery()->count(true);
    }

    /**
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\SpecificationQueryBuilder
     */
    protected function queryBuilder()
    {
        if ($this->queryBuilder === null) {
            $this->queryBuilder = new SpecificationQueryBuilder(
                $this->repository->getDocumentManager(),
                $this->repository->getDocumentName(),
                $this->specification()
            );

            if ($this->offset !== null) {
                $this->queryBuilder->skip($this->offset);
            }
            if ($this->length !== null) {
                $this->queryBuilder->limit($this->length);
            }

            if ($this->isSorted()) {
                $this->comparator()->accept($this->queryBuilder);
            }
        }

        return $this->queryBuilder;
    }
}
