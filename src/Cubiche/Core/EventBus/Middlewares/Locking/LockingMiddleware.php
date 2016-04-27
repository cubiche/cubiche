<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\EventBus\Middlewares\Locking;

use Cubiche\Core\EventBus\EventInterface;
use Cubiche\Core\EventBus\MiddlewareInterface;
use Cubiche\Core\Delegate\Delegate;

/**
 * LockingMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LockingMiddleware implements MiddlewareInterface
{
    /**
     * @var bool
     */
    private $isRunning;

    /**
     * @var Delegate[]
     */
    private $queue = [];

    /**
     * Execute the given event... after other running events are complete.
     *
     * @param EventInterface $event
     * @param callable       $next
     *
     * @return mixed|void
     *
     * @throws \Exception
     */
    public function handle(EventInterface $event, callable $next)
    {
        $this->queue[] = Delegate::fromClosure(function () use ($event, $next) {
            $next($event);
        });

        if ($this->isRunning) {
            return;
        }

        $this->isRunning = true;
        try {
            $this->runQueuedJobs();
        } catch (\Exception $e) {
            $this->isRunning = false;
            $this->queue = [];

            throw $e;
        }

        $this->isRunning = false;
    }

    /**
     * Process any pending event in the queue.
     */
    protected function runQueuedJobs()
    {
        while ($lastEvent = array_shift($this->queue)) {
            $lastEvent->__invoke();
        }
    }
}
