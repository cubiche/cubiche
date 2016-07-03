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
 * Dynamic Dispatch Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface DynamicDispatchVisitorInterface extends VisitorInterface
{
    /**
     * @param VisiteeInterface $visitee
     *
     * @return bool
     */
    public function canHandlerVisitee(VisiteeInterface $visitee);
}
