<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Comparable;

use Cubiche\Core\Visitor\Visitor;
use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Abstract Comparator Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ComparatorVisitor extends Visitor implements ComparatorVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(VisiteeInterface $visitee)
    {
        if ($visitee instanceof ComparatorInterface) {
            return $visitee->acceptComparatorVisitor($this);
        }

        return parent::visit($visitee);
    }
}
