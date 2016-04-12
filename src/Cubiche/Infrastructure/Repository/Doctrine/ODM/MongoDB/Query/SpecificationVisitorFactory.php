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
 * Specification Visitor Factory Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SpecificationVisitorFactory implements SpecificationVisitorFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @see SpecificationVisitorFactoryInterface::create()
     */
    public function create(QueryBuilder $queryBuilder)
    {
        return new SpecificationVisitor($queryBuilder);
    }
}
