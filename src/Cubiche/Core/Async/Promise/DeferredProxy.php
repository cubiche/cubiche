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
 * Deferred Proxy class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DeferredProxy implements DeferredInterface
{
    /**
     * @var DeferredInterface
     */
    protected $deferred;

    /**
     * @var Delegate
     */
    protected $onFulfilled;

    /**
     * @var Delegate
     */
    protected $onRejected;

    /**
     * @var Delegate
     */
    protected $onNotify;

    /**
     * @var bool
     */
    protected $notifyPropagation;

    /**
     * @param DeferredInterface $deferred
     * @param callable          $onFulfilled
     * @param callable          $onRejected
     * @param callable          $onNotify
     * @param bool              $notifyPropagation
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        DeferredInterface $deferred,
        callable $onFulfilled = null,
        callable $onRejected = null,
        callable $onNotify = null,
        $notifyPropagation = true
    ) {
        if (!$deferred->promise()->state()->equals(State::PENDING())) {
            throw new \InvalidArgumentException('The deferred target must be unresolved');
        }

        $this->deferred = $deferred;
        if ($onFulfilled !== null) {
            $this->onFulfilled = new Delegate($onFulfilled);
        }
        if ($onRejected !== null) {
            $this->onRejected = new Delegate($onRejected);
        }
        if ($onNotify !== null) {
            $this->onNotify = new Delegate($onNotify);
        }

        $this->notifyPropagation = $notifyPropagation;
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
    public function resolve($value = null)
    {
        try {
            if ($this->onFulfilled !== null) {
                $value = $this->onFulfilled->__invoke($value);
            }

            $this->deferred->resolve($value);
        } catch (\Throwable $e) {
            $this->reject($e);
        } catch (\Exception $e) {
            $this->reject($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reject($reason = null)
    {
        try {
            if ($this->onRejected !== null) {
                $this->onRejected->__invoke($reason);
            }
        } catch (\Throwable $e) {
            $reason = $e;
        } catch (\Exception $e) {
            $reason = $e;
        }

        $this->deferred->reject($reason);
    }

    /**
     * {@inheritdoc}
     */
    public function notify($state = null)
    {
        try {
            if ($this->onNotify !== null) {
                $this->onNotify->__invoke($state);
            }
            if ($this->notifyPropagation) {
                $this->deferred->notify($state);
            }
        } catch (\Throwable $e) {
            $this->reject($e);
        } catch (\Exception $e) {
            $this->reject($e);
        }
    }
}
