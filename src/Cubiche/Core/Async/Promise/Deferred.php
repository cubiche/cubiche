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

use Cubiche\Core\Delegate\Delegate;

/**
 * Deferred class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Deferred implements DeferredInterface
{
    /**
     * @var PromiseInterface
     */
    protected $promise;

    /**
     * @var Delegate
     */
    protected $resolveDelegate;

    /**
     * @var Delegate
     */
    protected $rejectDelegate;

    /**
     * @var Delegate
     */
    protected $notifyDelegate;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->promise = null;
    }

    /**
     * {@inheritdoc}
     */
    public function promise()
    {
        if ($this->promise === null) {
            $this->promise = new Promise(
                new Delegate(function (callable $callable) {
                    $this->resolveDelegate = new Delegate($callable);
                }),
                new Delegate(function (callable $callable) {
                    $this->rejectDelegate = new Delegate($callable);
                }),
                new Delegate(function (callable $callable) {
                    $this->notifyDelegate = new Delegate($callable);
                })
            );
        }

        return $this->promise;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($value = null)
    {
        $this->promise();

        $this->resolveDelegate->__invoke($value);
    }

    /**
     * {@inheritdoc}
     */
    public function reject($reason = null)
    {
        $this->promise();

        $this->rejectDelegate->__invoke($reason);
    }

    /**
     * {@inheritdoc}
     */
    public function notify($state = null)
    {
        $this->promise();

        $this->notifyDelegate->__invoke($state);
    }

    /**
     * @return bool
     */
    public function cancel()
    {
        if ($this->promise()->state()->equals(State::PENDING())) {
            $this->reject(new CancellationException());

            return true;
        }

        return false;
    }
}
