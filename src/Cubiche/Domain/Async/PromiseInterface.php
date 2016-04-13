<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Async;

use Cubiche\Core\Delegate\Delegate;

/**
 * Promise Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface PromiseInterface
{
    /**
     * @param Delegate $succeed
     * @param Delegate $rejected
     * @param Delegate $notify
     *
     * @return PromiseInterface
     */
    public function then(Delegate $succeed = null, Delegate $rejected = null, Delegate $notify = null);

    /**
     * @param Delegate $catch
     *
     * @return PromiseInterface
     */
    public function otherwise(Delegate $catch);

    /**
     * @param Delegate $finally
     *
     * @return PromiseInterface
     */
    public function always(Delegate $finally, Delegate $notify = null);
}
