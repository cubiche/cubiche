<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Repository\MongoDB\Visitor;

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Infrastructure\MongoDB\DocumentManager;
use Cubiche\Infrastructure\MongoDB\QueryBuilder\QueryBuilder as BaseQueryBuilder;

/**
 * QueryBuilder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryBuilder extends BaseQueryBuilder
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
    protected $factory;

    /**
     * QueryBuilder constructor.
     *
     * @param DocumentManager                           $dm
     * @param string                                    $documentName
     * @param SpecificationVisitorFactoryInterface|null $specificationVisitorFactory
     * @param ComparatorVisitorFactoryInterface|null    $comparatorVisitorFactory
     */
    public function __construct(
        DocumentManager $dm,
        $documentName,
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
     * @return \Cubiche\Infrastructure\MongoDB\QueryBuilder\Expression
     */
    public function getExpr()
    {
        return $this->expression;
    }

    /**
     * @return static
     */
    public function createQueryBuilder()
    {
        return $this->factory->__invoke();
    }
}
