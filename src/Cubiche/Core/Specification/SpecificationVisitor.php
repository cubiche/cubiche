<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Specification;

use Cubiche\Core\Visitor\Visitor;
use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Abstract Specification Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SpecificationVisitor extends Visitor implements SpecificationVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(VisiteeInterface $visitee)
    {
        if ($visitee instanceof SpecificationInterface) {
            return $visitee->acceptSpecificationVisitor($this);
        }

        return parent::visit($visitee);
    }
}
