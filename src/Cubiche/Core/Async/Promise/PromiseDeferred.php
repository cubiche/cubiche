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
 * Promise Deferred class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PromiseDeferred extends AbstractPromise implements DeferredInterface
{
    /**
     * @var DeferredInterface[]
     */
    private $deferreds = array();

    /**
     * @var PromiseInterface
     */
    private $actual = null;

    /**
     * @param callable $onFulfilled
     * @param callable $onRejected
     * @param callable $onNotify
     *
     * @return PromiseInterface
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        if ($this->state()->equals(State::PENDING())) {
            return $this->enqueue($onFulfilled, $onRejected, $onNotify)->promise();
        }

        return $this->actual->then($onFulfilled, $onRejected, $onNotify);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($value = null)
    {
        $this->changeState($value, true);
    }

    /**
     * {@inheritdoc}
     */
    public function reject($reason = null)
    {
        $this->changeState($reason, false);
    }

    /**
     * {@inheritdoc}
     */
    public function notify($state = null)
    {
        if ($this->state()->equals(State::PENDING())) {
            foreach ($this->deferreds as $deferred) {
                $deferred->notify($state);
            }
        } else {
            throw new \LogicException(\sprintf('A %s promise cannot be notified', $this->state()->getValue()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function state()
    {
        return $this->actual !== null ? $this->actual->state() : State::PENDING();
    }

    /**
     * {@inheritdoc}
     */
    public function promise()
    {
        return $this;
    }

    /**
     * @param mixed $result
     * @param bool  $success
     *
     * @throws \LogicException
     */
    private function changeState($result, $success)
    {
        if ($this->state()->equals(State::PENDING())) {
            $this->actual = $success ? new FulfilledPromise($result) : new RejectedPromise($result);

            while (!empty($this->deferreds)) {
                /** @var \Cubiche\Core\Async\Promise\DeferredInterface $deferred */
                $deferred = array_shift($this->deferreds);
                if ($success) {
                    $deferred->resolve($result);
                } else {
                    $deferred->reject($result);
                }
            }
        } else {
            throw new \LogicException(
                \sprintf('A %s promise cannot be %s', $this->state()->getValue(), $success ? 'resolved' : 'rejected')
            );
        }
    }

    /**
     * @param callable $onFulfilled
     * @param callable $onRejected
     * @param callable $onNotify
     *
     * @return \Cubiche\Core\Async\Promise\DeferredProxy
     */
    private function enqueue(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        $deferred = new DeferredProxy(new Deferred(), $onFulfilled, $onRejected, $onNotify, false);
        $this->deferreds[] = $deferred;

        return $deferred;
    }
}
