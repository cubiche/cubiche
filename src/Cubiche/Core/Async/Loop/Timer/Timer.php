<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Loop\Timer;

use Cubiche\Core\Async\Promise\Deferred;
use Cubiche\Core\Async\Promise\DeferredInterface;
use Cubiche\Core\Delegate\Delegate;
use React\EventLoop\LoopInterface;
use React\EventLoop\Timer\TimerInterface as BaseTimerInterface;

/**
 * Timer Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Timer implements TimerInterface
{
    /**
     * @var int
     */
    protected $maxIterations;

    /**
     * @var int
     */
    protected $iterations;

    /**
     * @var BaseTimerInterface
     */
    private $timer;

    /**
     * @var Delegate
     */
    private $task;

    /**
     * @var DeferredInterface
     */
    private $deferred = null;

    /**
     * @var mixed
     */
    private $lastResult;

    /**
     * @param LoopInterface $loop
     * @param callable      $task
     * @param int|float     $interval
     * @param bool          $periodic
     * @param int           $count
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(LoopInterface $loop, callable $task, $interval, $periodic = false, $count = null)
    {
        $this->maxIterations = $count !== null ? (int) $count : ($periodic ? null : 1);
        if ($this->maxIterations !== null && $this->maxIterations <= 0) {
            throw new \InvalidArgumentException('The count argument must be a positive integer value');
        }

        $this->task = new Delegate($task);
        $this->iterations = 0;
        $onTick = function () {
            $this->onTick();
        };
        if ($periodic) {
            $this->timer = $loop->addPeriodicTimer($interval, $onTick);
        } else {
            $this->timer = $loop->addTimer($interval, $onTick);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function interval()
    {
        return $this->timer->getInterval();
    }

    /**
     * {@inheritdoc}
     */
    public function iterations()
    {
        return $this->iterations;
    }

    /**
     * {@inheritdoc}
     */
    public function maxIterations()
    {
        return $this->maxIterations;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->timer->isActive();
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        $this->timer->cancel();
        $this->deferred()->cancel();
    }

    /**
     * {@inheritdoc}
     */
    public function then(callable $onSucceed = null, callable $onRejected = null, callable $onNotify = null)
    {
        return $this->deferred()->promise()->then($onSucceed, $onRejected, $onNotify);
    }

    /**
     * {@inheritdoc}
     */
    public function otherwise(callable $onRejected)
    {
        return $this->deferred()->promise()->otherwise($onRejected);
    }

    /**
     * {@inheritdoc}
     */
    public function always(callable $onFulfilledOrRejected, callable $notify = null)
    {
        return $this->deferred()->promise()->always($onFulfilledOrRejected, $notify);
    }

    /**
     * {@inheritdoc}
     */
    public function state()
    {
        return $this->deferred()->promise()->state();
    }

    /**
     * @return \Cubiche\Core\Async\Promise\Deferred
     */
    protected function deferred()
    {
        if ($this->deferred === null) {
            $this->deferred = new Deferred();
        }

        return $this->deferred;
    }

    /**
     */
    private function onTick()
    {
        try {
            $this->lastResult = $this->task->__invoke($this);
            ++$this->iterations;
            if ($this->timer->isPeriodic()) {
                $this->deferred()->notify($this->lastResult);
                if ($this->maxIterations() !== null && $this->iterations() >= $this->maxIterations()) {
                    $this->cancel();
                }
            } else {
                $this->deferred()->resolve($this->lastResult);
            }
        } catch (\Exception $e) {
            $this->deferred()->reject($e);
            $this->timer->cancel();
        }
    }
}
