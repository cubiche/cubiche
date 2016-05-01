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
 * Then Resolver class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ThenResolver extends ObservableResolver implements PromisorInterface
{
    /**
     * @var DeferredInterface
     */
    protected $deferred;

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
        $this->deferred = new Deferred();
    }

    /**
     * {@inheritdoc}
     */
    public function reject($reason = null)
    {
        try {
            parent::reject($reason);
        } catch (\Exception $e) {
            $reason = $e;
        }

        $this->deferred->reject($reason);
    }

    /**
     * {@inheritdoc}
     */
    public function promise()
    {
        return $this->deferred->promise();
    }

    /**
     * {@inheritdoc}
     */
    protected function onResolve($value = null)
    {
        $value = $this->callResolveCallback($value);
        if ($value instanceof PromiseInterface) {
            $value->done(function ($actual) {
                $this->deferred->resolve($actual);
            }, function ($reason) {
                $this->deferred->reject($reason);
            }, function ($state) {
                $this->deferred->notify($state);
            });
        } else {
            $this->deferred->resolve($value);
        }
    }
}
