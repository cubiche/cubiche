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

use Cubiche\Core\Comparable\ComparatorVisitorInterface;

/**
 * Comparator Visitor Factory Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ComparatorVisitorFactoryInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return ComparatorVisitorInterface
     */
    public function create(QueryBuilder $queryBuilder);
}
