<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

use Cubiche\Core\Visitor\Visitor;
use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Abstract Selector Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SelectorVisitor extends Visitor implements SelectorVisitorInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Visitor::visit()
     */
    public function visit(VisiteeInterface $visitee)
    {
        if ($visitee instanceof SelectorInterface) {
            return $visitee->acceptSelectorVisitor($this);
        }

        return parent::visit($visitee);
    }
}
