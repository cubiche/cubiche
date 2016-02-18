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

use Cubiche\Domain\Collections\Specification\SpecificationInterface;
use Cubiche\Domain\Model\EntityInterface;
use Cubiche\Domain\Persistence\RepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentRepository as MongoDBDocumentRepository;

/**
 * Document Repository Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentRepository implements RepositoryInterface
{
    /**
     * @var MongoDBDocumentRepository
     */
    protected $repository;

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function dm()
    {
        return $this->repository->getDocumentManager();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::add()
     */
    public function add($item)
    {
        $this->dm()->persist($item);
        $this->dm()->flush();
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
     * @see \Cubiche\Domain\Collections\CollectionInterface::contains()
     */
    public function contains($item)
    {
        if ($item instanceof EntityInterface) {
            return $this->exists($item->id());
        }

        //TODO
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::exists()
     */
    public function exists($key)
    {
        return $this->get($key) !== null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::get()
     */
    public function get($key)
    {
        return $this->repository->find($key);
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
        //TODO
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::slice()
     */
    public function slice($offset, $length = null)
    {
        //TODO
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::find()
     */
    public function find(SpecificationInterface $specification)
    {
        //TODO
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\CollectionInterface::toArray()
     */
    public function toArray()
    {
        //TODO
    }
}
