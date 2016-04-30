<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Promise;

/**
 * Promise Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface PromiseInterface extends ThenableInterface
{
    /**
     * @param callable $onFulfilled
     * @param callable $onRejected
     * @param callable $onNotify
     *
     * @return PromiseInterface
     */
    public function done(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null);

    /**
     * @param callable $onRejected
     *
     * @return PromiseInterface
     */
    public function otherwise(callable $onRejected);

    /**
     * @param callable $onFulfilledOrRejected
     * @param callable $onNotify
     *
     * @return PromiseInterface
     */
    public function always(callable $onFulfilledOrRejected, callable $onNotify = null);

    /**
     * @return State
     */
    public function state();
}
