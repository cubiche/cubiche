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

/**
 * Comparator Visitor Factory Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ComparatorVisitorFactory implements ComparatorVisitorFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query\ComparatorVisitorFactoryInterface::create()
     */
    public function create(QueryBuilder $queryBuilder)
    {
        return new ComparatorVisitor($queryBuilder);
    }
}
