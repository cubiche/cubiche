<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventBus;

/**
 * Middleware interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MiddlewareInterface
{
    /**
     * @param EventInterface $event
     * @param callable       $next
     */
    public function handle(EventInterface $event, callable $next);
}
