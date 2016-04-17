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
interface PromiseInterface
{
    /**
     * @param callable $succeed
     * @param callable $rejected
     * @param callable $notify
     *
     * @return PromiseInterface
     */
    public function then(callable $succeed = null, callable $rejected = null, callable $notify = null);

    /**
     * @param callable $catch
     *
     * @return PromiseInterface
     */
    public function otherwise(callable $catch);

    /**
     * @param callable $finally
     *
     * @return PromiseInterface
     */
    public function always(callable $finally, callable $notify = null);
}
