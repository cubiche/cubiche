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
 * Visitor interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface VisitorInterface
{
    /**
     * @param VisiteeInterface $visitee
     *
     * @return mixed
     */
    public function visit(VisiteeInterface $visitee);
}
