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
 * Promise.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Promise implements PromiseInterface
{
    const WAITING = 'waiting';
    const COMPLETED = 'completed';
    const REJECTED = 'rejected';

    /**
     * @var string
     */
    private $state;

    /**
     * @var Delegate[]
     */
    private $succeedDelegates;

    /**
     * @var Delegate[]
     */
    private $rejectedDelegates;

    /**
     * @var Delegate[]
     */
    private $notifyDelegates;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @param callable $exportResolve
     * @param callable $exportReject
     * @param callable $exportNotify
     * @param callable $exportCancel
     */
    public function __construct(
        callable $exportResolve,
        callable $exportReject,
        callable $exportNotify,
        callable $exportCancel
    ) {
        $this->state = self::WAITING;
        $this->succeedDelegates = array();
        $this->rejectedDelegates = array();
        $this->notifyDelegates = array();

        $exportResolve(function ($value = null) {
            return $this->resolve($value);
        });
        $exportReject(function ($reason = null) {
            return $this->reject($reason);
        });
        $exportNotify(function ($state = null) {
            return $this->notify($state);
        });
        $exportCancel(function () {
            return $this->cancel();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function then(callable $succeed = null, callable $rejected = null, callable $notify = null)
    {
        $deferred = Deferred::defer();

        $this->addSucceedDelegate($deferred, $succeed);
        $this->addRejectedDelegate($deferred, $rejected);
        $this->addNotifyDelegate($notify);

        $this->resolver();

        return $deferred->promise();
    }

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
    public function always(callable $finally, callable $notify = null)
    {
        return $this->then(function ($value) use ($finally) {
            $finally($value, null);

            return $value;
        }, function ($reason) use ($finally) {
            $finally(null, $reason);
        }, $notify);
    }

    /**
     * @param mixed $value
     */
    protected function resolve($value = null)
    {
        $this->result(self::COMPLETED, $value);
    }

    /**
     * @param mixed $reason
     */
    protected function reject($reason = null)
    {
        $this->result(self::REJECTED, $reason);
    }

    /**
     * @param mixed $state
     */
    protected function notify($state = null)
    {
        if ($this->state === self::WAITING) {
            foreach ($this->notifyDelegates as $delegate) {
                $delegate($state);
            }
        } else {
            throw new \LogicException(\sprintf('A %s promise cannot be notified', $this->state));
        }
    }

    /**
     * @return bool
     */
    protected function cancel()
    {
        if ($this->state !== self::WAITING) {
            return false;
        }
        $this->reject(new \RuntimeException('Promise has been cancelled'));

        return true;
    }

    /**
     * @param DeferredInterface $deferred
     * @param callable          $succeed
     */
    private function addSucceedDelegate(DeferredInterface $deferred, callable $succeed = null)
    {
        if ($this->state === self::WAITING || $this->state === self::COMPLETED) {
            $this->succeedDelegates[] = new Delegate(function ($value = null) use ($succeed, $deferred) {
                $actual = $value;
                if ($succeed !== null) {
                    $actual = $succeed($value);
                }

                $deferred->resolve($actual !== null ? $actual : $value);
            });
        }
    }

    /**
     * @param DeferredInterface $deferred
     * @param callable          $rejected
     */
    private function addRejectedDelegate(DeferredInterface $deferred, callable $rejected = null)
    {
        if ($this->state === self::WAITING || $this->state === self::REJECTED) {
            $this->rejectedDelegates[] = new Delegate(function ($reason = null) use ($rejected, $deferred) {
                if ($rejected !== null) {
                    $rejected($reason);
                }

                $deferred->reject($reason);
            });
        }
    }

    /**
     * @param callable $notify
     */
    private function addNotifyDelegate(callable $notify = null)
    {
        if ($this->state === self::WAITING && $notify !== null) {
            $this->notifyDelegates[] = new Delegate($notify);
        }
    }

    /**
     * @param string $state
     * @param mixed  $result
     *
     * @throws \LogicException
     */
    private function result($state, $result)
    {
        if ($this->state === self::WAITING) {
            $this->state = $state;
            $this->result = $result;

            $this->resolver();
        } else {
            throw new \LogicException(\sprintf('A %s promise cannot be resolved or rejected', $this->state));
        }
    }

    private function resolver()
    {
        if ($this->state === self::WAITING) {
            return;
        }

        if ($this->state === self::COMPLETED) {
            $this->resolveDelegates($this->succeedDelegates);
        } elseif ($this->state === self::REJECTED) {
            $this->resolveDelegates($this->rejectedDelegates);
        }
    }

    /**
     * @param array $delegates
     */
    private function resolveDelegates(array &$delegates)
    {
        foreach ($delegates as $i => $delegate) {
            $delegate($this->result);
            unset($delegates[$i]);
        }
    }
}
