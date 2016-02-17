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

use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * Query Builder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class QueryBuilder extends Builder
{
    /**
     * @return string
     */
    public function currentField()
    {
        return $this->currentField;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Query\Expr
     */
    public function currentExpr()
    {
        $expr = $this->expr();
        $expr->setQuery($this->getQueryArray());

        return $expr;
    }
}
