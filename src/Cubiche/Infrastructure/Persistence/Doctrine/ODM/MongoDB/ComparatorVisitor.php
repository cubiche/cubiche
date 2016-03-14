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

use Cubiche\Domain\Collections\Comparator\ComparatorVisitorInterface;
use Cubiche\Domain\Collections\Comparator\SelectorComparator;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\Custom as CustomComparator;
use Cubiche\Domain\Comparable\MultiComparator;

/**
 * Comparator Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ComparatorVisitor extends AbstractCriteriaVisitor implements ComparatorVisitorInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorVisitorInterface::visitComparator()
     */
    public function visitComparator(Comparator $comparator)
    {
        $this->notSupportedException($comparator);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorVisitorInterface::visitCustomComparator()
     */
    public function visitCustomComparator(CustomComparator $comparator)
    {
        $this->notSupportedException($comparator);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorVisitorInterface::visitMultiComparator()
     */
    public function visitMultiComparator(MultiComparator $comparator)
    {
        $comparator->firstComparator()->accept($this);
        $comparator->secondComparator()->accept($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Comparator\ComparatorVisitorInterface::visitSelectorComparator()
     */
    public function visitSelectorComparator(SelectorComparator $comparator)
    {
        $field = $this->createField($comparator->selector());
        $this->queryBuilder->sort($field->name(), $comparator->order()->toNative());
    }
}
