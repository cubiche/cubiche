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

use Cubiche\Core\Visitor\VisitorInterface;

/**
 * Specification Visitor Factory Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SpecificationVisitorFactoryInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return VisitorInterface
     */
    public function create(QueryBuilder $queryBuilder);
}
