<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Loop;

use React\EventLoop\LoopInterface as BaseLoopInterface;
use React\EventLoop\Timer\TimerInterface;

/**
 * Loop Adapter Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LoopAdapter extends Loop implements BaseLoopInterface
{
    /**
     * {@inheritdoc}
     */
    public function addReadStream($stream, callable $listener)
    {
        return $this->loop()->addReadStream($stream, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function addWriteStream($stream, callable $listener)
    {
        return $this->loop()->addWriteStream($stream, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function removeReadStream($stream)
    {
        return $this->loop()->removeReadStream($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function removeWriteStream($stream)
    {
        return $this->loop()->removeWriteStream($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function removeStream($stream)
    {
        return $this->loop()->removeStream($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function addTimer($interval, callable $callback)
    {
        return $this->loop()->addTimer($interval, $callback);
    }

    /**
     * {@inheritdoc}
     */
    public function addPeriodicTimer($interval, callable $callback)
    {
        return $this->loop()->addPeriodicTimer($interval, $callback);
    }

    /**
     * {@inheritdoc}
     */
    public function cancelTimer(TimerInterface $timer)
    {
        return $this->loop()->cancelTimer($timer);
    }

    /**
     * {@inheritdoc}
     */
    public function isTimerActive(TimerInterface $timer)
    {
        return $this->loop()->isTimerActive($timer);
    }

    /**
     * {@inheritdoc}
     */
    public function nextTick(callable $listener)
    {
        return $this->loop()->nextTick($listener);
    }

    /**
     * {@inheritdoc}
     */
    public function futureTick(callable $listener)
    {
        return $this->loop()->futureTick($listener);
    }

    /**
     * {@inheritdoc}
     */
    public function tick()
    {
        return $this->loop()->tick();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->loop()->run();
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        return $this->loop()->stop();
    }
}
