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

use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\Repository;
use Doctrine\ODM\MongoDB\DocumentRepository as MongoDBDocumentRepository;

/**
 * Document Repository Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentRepository extends Repository
{
    /**
     * @var MongoDBDocumentRepository
     */
    protected $repository;

    /**
     * @param MongoDBDocumentRepository $repository
     */
    public function __construct(MongoDBDocumentRepository $repository)
    {
        parent::__construct($repository->getDocumentName());

        $this->repository = $repository;
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
        $this->dm()->flush($element);
    }

    /**
     * {@inheritdoc}
     */
    public function persistAll($elements)
    {
        foreach ($elements as $element) {
            $this->checkType($element);
            $this->dm()->persist($element);
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
    public function getIterator()
    {
        return $this->repository->createQueryBuilder()->getQuery()->getIterator();
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function dm()
    {
        return $this->repository->getDocumentManager();
    }
}
