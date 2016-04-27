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

use Cubiche\Core\Async\Loop\Timer\Timer;
use Cubiche\Core\Async\Promise\CallablePromisor;
use Cubiche\Core\Async\Promise\PromiseInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface as BaseLoopInterface;

/**
 * Loop Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Loop implements LoopInterface
{
    /**
     * @var BaseLoopInterface
     */
    protected $loop = null;

    /**
     * @param BaseLoopInterface $loop
     */
    public function __construct(BaseLoopInterface $loop = null)
    {
        $this->loop = $loop;
        if ($this->loop === null) {
            $this->loop = Factory::create();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addReadStream($stream, callable $listener)
    {
        $this->loop()->addReadStream($stream, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function addWriteStream($stream, callable $listener)
    {
        $this->loop()->addWriteStream($stream, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function removeReadStream($stream)
    {
        $this->loop()->removeReadStream($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function removeWriteStream($stream)
    {
        $this->loop()->removeWriteStream($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function removeStream($stream)
    {
        $this->loop()->removeStream($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function timeout(callable $task, $delay)
    {
        return new Timer($this->loop(), $task, $delay);
    }

    /**
     * {@inheritdoc}
     */
    public function timer(callable $task, $interval, $count = null)
    {
        return new Timer($this->loop(), $task, $interval, true, $count);
    }

    /**
     * @param callable $task
     *
     * @return PromiseInterface
     */
    public function next(callable $task)
    {
        $promisor = new CallablePromisor($task);
        $this->loop()->nextTick($promisor);

        return $promisor->promise();
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(callable $task)
    {
        $promisor = new CallablePromisor($task);
        $this->loop()->futureTick($promisor);

        return $promisor->promise();
    }

    /**
     * {@inheritdoc}
     */
    public function tick()
    {
        $this->loop()->tick();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->loop()->run();
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        $this->loop()->stop();
    }

    /**
     * @return \React\EventLoop\LoopInterface
     */
    protected function loop()
    {
        return $this->loop;
    }
}
