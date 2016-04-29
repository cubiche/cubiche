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

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorVisitor as BaseComparatorVisitor;
use Cubiche\Core\Comparable\Custom;
use Cubiche\Core\Comparable\MultiComparator;
use Cubiche\Core\Comparable\ReverseComparator;
use Cubiche\Core\Comparable\SelectorComparator;

/**
 * Comparator Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ComparatorVisitor extends BaseComparatorVisitor
{
    use VisitorTrait;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function visitComparator(Comparator $comparator)
    {
        throw $this->notSupportedException($comparator);
    }

    /**
     * {@inheritdoc}
     */
    public function visitReverseComparator(ReverseComparator $comparator)
    {
        $reverse = $comparator->comparator()->reverse();
        if ($reverse instanceof ReverseComparator) {
            throw $this->notSupportedException($comparator);
        }

        return $reverse->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    public function visitCustomComparator(Custom $comparator)
    {
        $this->notSupportedException($comparator);
    }

    /**
     * {@inheritdoc}
     */
    public function visitMultiComparator(MultiComparator $comparator)
    {
        $comparator->firstComparator()->accept($this);
        $comparator->secondComparator()->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    public function visitSelectorComparator(SelectorComparator $comparator)
    {
        $field = $this->createField($comparator->selector());
        $this->queryBuilder->sort($field->name(), $comparator->order()->getValue());
    }
}
