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
use Cubiche\Domain\Collections\Specification\SpecificationInterface;
use Doctrine\ODM\MongoDB\DocumentRepository as MongoDBDocumentRepository;

/**
 * DocumentFinder Class.
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
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @param array                  $items
     * @param SpecificationInterface $specification
     * @param int                    $offset
     * @param int                    $length
     */
    public function __construct(
        MongoDBDocumentRepository $repository,
        SpecificationInterface $specification,
        $offset = null,
        $length = null
    ) {
        parent::__construct($specification, $offset, $length);
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
        return new self($this->repository, $this->specification, $offset, $length);
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
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function queryBuilder()
    {
        if ($this->queryBuilder === null) {
            $visitor = new SpecificationVisitor(
                $this->repository->getDocumentManager(),
                $this->repository->getDocumentName()
            );
            $this->queryBuilder = $visitor->queryBuilder($this->specification);
            if ($this->offset !== null) {
                $this->queryBuilder->skip($this->offset);
            }
            if ($this->length !== null) {
                $this->queryBuilder->limit($this->length);
            }
        }

        return $this->queryBuilder;
    }
}
