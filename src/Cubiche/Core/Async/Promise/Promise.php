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
 * Promise class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Promise extends AbstractPromise
{
    /**
     * @var DeferredInterface
     */
    private $deferred;

    /**
     * @param callable $exportResolve
     * @param callable $exportReject
     * @param callable $exportNotify
     */
    public function __construct(
        callable $exportResolve,
        callable $exportReject,
        callable $exportNotify
    ) {
        $this->deferred = new PromiseDeferred();

        $exportResolve(function ($value = null) {
            return $this->resolve($value);
        });
        $exportReject(function ($reason = null) {
            return $this->reject($reason);
        });
        $exportNotify(function ($state = null) {
            return $this->notify($state);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        return $this->deferred->promise()->then($onFulfilled, $onRejected, $onNotify);
    }

    /**
     * {@inheritdoc}
     */
    public function done(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        $this->deferred->promise()->done($onFulfilled, $onRejected, $onNotify);
    }

    /**
     * {@inheritdoc}
     */
    public function state()
    {
        return $this->deferred->promise()->state();
    }

    /**
     * @param mixed $value
     */
    protected function resolve($value = null)
    {
        $this->deferred->resolve($value);
    }

    /**
     * @param mixed $reason
     */
    protected function reject($reason = null)
    {
        $this->deferred->reject($reason);
    }

    /**
     * @param mixed $state
     */
    protected function notify($state = null)
    {
        $this->deferred->notify($state);
    }
}
