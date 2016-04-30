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
 * Abstract Promise class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class AbstractPromise implements PromiseInterface
{
    /**
     * {@inheritdoc}
     */
    public function done(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        return $this->then(function ($value = null) use ($onFulfilled) {
            if ($onFulfilled !== null) {
                $onFulfilled($value);
            }

            return $value;
        }, $onRejected);
    }

    /**
     * {@inheritdoc}
     */
    public function otherwise(callable $onRejected)
    {
        return $this->then(null, $onRejected);
    }

    /**
     * {@inheritdoc}
     */
    public function always(callable $onFulfilledOrRejected, callable $onNotify = null)
    {
        return $this->done($onFulfilledOrRejected, $onFulfilledOrRejected, $onNotify);
    }
}
