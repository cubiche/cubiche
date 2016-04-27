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
    public function otherwise(callable $catch)
    {
        return $this->then(null, $catch);
    }

    /**
     * {@inheritdoc}
     */
    public function always(callable $finally, callable $onNotify = null)
    {
        return $this->then(function ($value) use ($finally) {
            $finally($value, null);

            return $value;
        }, function ($reason) use ($finally) {
            $finally(null, $reason);
        }, $onNotify);
    }
}
