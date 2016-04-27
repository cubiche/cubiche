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

use Cubiche\Core\Async\Promise\PromiseInterface;
use Cubiche\Core\Async\Loop\Timer\TimerInterface;

/**
 * Loop Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface LoopInterface
{
    /**
     * @param resource $stream
     * @param callable $listener
     */
    public function addReadStream($stream, callable $listener);

    /**
     * @param resource $stream
     * @param callable $listener
     */
    public function addWriteStream($stream, callable $listener);

    /**
     * @param resource $stream
     */
    public function removeReadStream($stream);

    /**
     * @param resource $stream
     */
    public function removeWriteStream($stream);

    /**
     *  @param resource $stream
     */
    public function removeStream($stream);

    /**
     * @param callable  $task
     * @param int|float $delay
     *
     * @return TimerInterface
     */
    public function timeout(callable $task, $delay);

    /**
     * @param callable  $task
     * @param int|float $interval
     *
     * @return TimerInterface
     */
    public function timer(callable $task, $interval, $count = null);

    /**
     * @param callable $task
     *
     * @return PromiseInterface
     */
    public function next(callable $task);

    /**
     * @param callable $task
     *
     * @return PromiseInterface
     */
    public function enqueue(callable $task);

    /**
     */
    public function tick();

    /**
     */
    public function run();

    /**
     */
    public function stop();
}
