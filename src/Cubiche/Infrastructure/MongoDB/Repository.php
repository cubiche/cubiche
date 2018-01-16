<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB;

use Cubiche\Domain\Identity\IdentifiableInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Infrastructure\MongoDB\QueryBuilder\QueryBuilder;

/**
 * Repository class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Repository
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
     * Repository constructor.
     *
     * @param DocumentManager $dm
     * @param string          $documentName
     */
    public function __construct(DocumentManager $dm, $documentName)
    {
        $this->dm = $dm;
        $this->documentName = $documentName;
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return $this->dm->createQueryBuilder($this->documentName);
    }

    /**
     * @return string
     */
    public function documentName()
    {
        return $this->documentName;
    }

    /**
     * @param IdInterface $id
     *
     * @return array|null|object
     */
    public function get(IdInterface $id)
    {
        return $this->createQueryBuilder()
            ->field('id')->equals($id->toNative())
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @param IdentifiableInterface $element
     */
    public function persist(IdentifiableInterface $element)
    {
        $this->dm->persist($element);
    }

    /**
     * @param IdentifiableInterface[] $elements
     */
    public function persistAll($elements)
    {
        $this->dm->persistAll($elements);
    }

    /**
     * @param IdentifiableInterface $element
     */
    public function remove(IdentifiableInterface $element)
    {
        $this->dm->remove($element);
    }
}
