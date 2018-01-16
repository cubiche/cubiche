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

use Cubiche\Core\Visitor\VisitorInterface;

/**
 * SpecificationVisitorFactory interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
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
