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

use Cubiche\Domain\Delegate\Delegate;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * Query Builder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class QueryBuilder extends Builder
{
    /**
     * @var Delegate
     */
    private $factory;

    /**
     * @param DocumentManager $dm
     * @param string          $documentName
     */
    public function __construct(DocumentManager $dm, $documentName = null)
    {
        parent::__construct($dm, $documentName);

        $this->factory = Delegate::fromClosure(
            function () use ($dm, $documentName) {
                return new static($dm, $documentName);
            }
        );
    }

    /**
     * @param SpecificationInterface $criteria
     *
     * @return $this
     */
    public function addSearchCriteria(SpecificationInterface $criteria)
    {
        $criteria->accept(new SpecificationVisitor($this));

        return $this;
    }

    /**
     * @param ComparatorInterface $criteria
     *
     * @return $this
     */
    public function addSortCriteria(ComparatorInterface $criteria)
    {
        $criteria->accept(new ComparatorVisitor($this));

        return $this;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Query\Expr
     */
    public function getExpr()
    {
        $expr = $this->expr();
        $expr->setQuery($this->getQueryArray());

        return $expr;
    }

    /**
     * @return static
     */
    public function createQueryBuilder()
    {
        return $this->factory->__invoke();
    }
}
