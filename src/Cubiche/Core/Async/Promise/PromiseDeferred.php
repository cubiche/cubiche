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
    use CancelDeferredTrait;

    /**
     * @var ResolverInterface[]
     */
    private $resolvers = array();

    /**
     * @var PromiseInterface
     */
    private $actual = null;

    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        if ($this->state()->equals(State::PENDING())) {
            $resolver = new ThenResolver($onFulfilled, $onRejected, $onNotify);
            $this->resolvers[] = $resolver;

            return $resolver->promise();
        }

        return $this->actual->then($onFulfilled, $onRejected, $onNotify);
    }

    /**
     * {@inheritdoc}
     */
    public function done(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        if ($this->state()->equals(State::PENDING())) {
            $resolver = new DoneResolver($onFulfilled, $onRejected, $onNotify);
            $this->resolvers[] = $resolver;
        } else {
            $this->actual->done($onFulfilled, $onRejected, $onNotify);
        }
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
            foreach ($this->resolvers as $resolver) {
                $resolver->notify($state);
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

            while (!empty($this->resolvers)) {
                /** @var \Cubiche\Core\Async\Promise\ResolverInterface $resolver */
                $resolver = array_shift($this->resolvers);
                if ($success) {
                    $resolver->resolve($result);
                } else {
                    $resolver->reject($result);
                }
            }
        } else {
            throw new \LogicException(
                \sprintf('A %s promise cannot be %s', $this->state()->getValue(), $success ? 'resolved' : 'rejected')
            );
        }
    }
}
