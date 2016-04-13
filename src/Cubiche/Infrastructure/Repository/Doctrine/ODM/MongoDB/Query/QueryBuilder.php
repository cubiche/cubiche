<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query;

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Delegate\Delegate;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * Query Builder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class QueryBuilder extends Builder
{
    /**
     * @var SpecificationVisitorFactoryInterface
     */
    protected $specificationVisitorFactory;

    /**
     * @var ComparatorVisitorFactoryInterface
     */
    protected $comparatorVisitorFactory;

    /**
     * @var Delegate
     */
    private $factory;

    /**
     * @param DocumentManager                      $dm
     * @param string                               $documentName
     * @param SpecificationVisitorFactoryInterface $specificationVisitorFactory
     * @param ComparatorVisitorFactoryInterface    $comparatorVisitorFactory
     */
    public function __construct(
        DocumentManager $dm,
        $documentName = null,
        SpecificationVisitorFactoryInterface $specificationVisitorFactory = null,
        ComparatorVisitorFactoryInterface $comparatorVisitorFactory = null
    ) {
        parent::__construct($dm, $documentName);

        if ($specificationVisitorFactory === null) {
            $specificationVisitorFactory = new SpecificationVisitorFactory();
        }
        if ($comparatorVisitorFactory === null) {
            $comparatorVisitorFactory = new ComparatorVisitorFactory();
        }

        $this->specificationVisitorFactory = $specificationVisitorFactory;
        $this->comparatorVisitorFactory = $comparatorVisitorFactory;

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
        $criteria->accept($this->specificationVisitorFactory->create($this));

        return $this;
    }

    /**
     * @param ComparatorInterface $criteria
     *
     * @return $this
     */
    public function addSortCriteria(ComparatorInterface $criteria)
    {
        $criteria->accept($this->comparatorVisitorFactory->create($this));

        return $this;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Query\Expr
     */
    public function getExpr()
    {
        return $this->expr;
    }

    /**
     * @return static
     */
    public function createQueryBuilder()
    {
        return $this->factory->__invoke();
    }
}
