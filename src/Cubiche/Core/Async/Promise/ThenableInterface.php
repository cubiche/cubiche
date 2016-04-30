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
 * Thenable Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ThenableInterface
{
    /**
     * @param callable $onFulfilled
     * @param callable $onRejected
     * @param callable $onNotify
     *
     * @return static
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null);
}
