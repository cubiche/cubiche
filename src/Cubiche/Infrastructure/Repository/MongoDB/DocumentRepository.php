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

use Cubiche\Domain\EventSourcing\AggregateRootInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Infrastructure\MongoDB\Repository;

/**
 * DocumentRepository class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentRepository implements RepositoryInterface
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * DocumentRepository constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
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
    public function persist(AggregateRootInterface $element)
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
    public function remove(AggregateRootInterface $element)
    {
        $this->repository->remove($element);
    }
}
