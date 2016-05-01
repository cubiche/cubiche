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
 * Done Resolver class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DoneResolver extends ObservableResolver
{
    /**
     * @param callable $onFulfilled
     * @param callable $onRejected
     * @param callable $onNotify
     */
    public function __construct(
        callable $onFulfilled = null,
        callable $onRejected = null,
        callable $onNotify = null
    ) {
        parent::__construct($onFulfilled, $onRejected, $onNotify);
    }

    /**
     * {@inheritdoc}
     */
    protected function onInnerFailure(\Exception $reason)
    {
        throw $reason;
    }
}
