<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Async;

use Cubiche\Domain\Delegate\Delegate;

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
     * @param Delegate $resolveDeferred
     * @param Delegate $rejectDeferred
     * @param Delegate $notifyDeferred
     * @param Delegate $cancelDeferred
     */
    public function __construct(
        Delegate $resolveDeferred,
        Delegate $rejectDeferred,
        Delegate $notifyDeferred,
        Delegate $cancelDeferred
    ) {
        $this->state = self::WAITING;
        $this->succeedDelegates = array();
        $this->rejectedDelegates = array();
        $this->notifyDelegates = array();

        $resolveDeferred(new Delegate(function ($value = null) {
            $this->resolve($value);
        }));
        $rejectDeferred(new Delegate(function ($reason = null) {
            $this->reject($reason);
        }));
        $notifyDeferred(new Delegate(function ($state = null) {
            $this->notify($state);
        }));
        $cancelDeferred(new Delegate(function () {
            return $this->cancel();
        }));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\PromiseInterface::then()
     */
    public function then(Delegate $succeed = null, Delegate $rejected = null, Delegate $notify = null)
    {
        $deferred = Deferred::defer();

        $this->addSucceedDelegate($deferred, $succeed);
        $this->addRejectedDelegate($deferred, $rejected);
        $this->addNotifyDelegate($notify);

        $this->resolver();

        return $deferred->promise();
    }

    /**
     * @param DeferredInterface $deferred
     * @param Delegate          $succeed
     */
    private function addSucceedDelegate(DeferredInterface $deferred, Delegate $succeed = null)
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
     * @param Delegate          $rejected
     */
    private function addRejectedDelegate(DeferredInterface $deferred, Delegate $rejected = null)
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
     * @param Delegate $notify
     */
    private function addNotifyDelegate(Delegate $notify = null)
    {
        if ($this->state === self::WAITING && $notify !== null) {
            $this->notifyDelegates[] = $notify;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\PromiseInterface::otherwise()
     */
    public function otherwise(Delegate $catch)
    {
        return $this->then(null, $catch);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\PromiseInterface::always()
     */
    public function always(Delegate $finally, Delegate $notify = null)
    {
        return $this->then(new Delegate(function ($value) use ($finally) {
            $finally($value, null);

            return $value;
        }), new Delegate(function ($reason) use ($finally) {
            $finally(null, $reason);
        }), $notify);
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
