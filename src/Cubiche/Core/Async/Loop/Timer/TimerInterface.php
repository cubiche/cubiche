<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Loop\Timer;

use Cubiche\Core\Async\Promise\PromiseInterface;

/**
 * Timer Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface TimerInterface extends PromiseInterface
{
    /**
     * @return float
     */
    public function interval();

    /**
     * @return int
     */
    public function iterations();

    /**
     * @return int
     */
    public function maxIterations();

    /**
     * @return bool
     */
    public function isActive();

    /**
     */
    public function cancel();
}
