<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Visitor;

/**
 * Visitee Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Visitee implements VisiteeInterface
{
    /**
     * {@inheritdoc}
     */
    public function accept(VisitorInterface $visitor)
    {
        return $visitor->visit($this);
    }
}
