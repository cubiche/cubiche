<?php

/**
 * This file is part of the Cubiche/Bus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares;

use Cubiche\Core\Bus\MessageInterface;
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
     * Execute the given message... after other running messages are complete.
     *
     * @throws \Exception
     */
    public function handle($message, callable $next)
    {
        $this->queue[] = Delegate::fromClosure(function () use ($message, $next) {
            return $next($message);
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
     * Process any pending message in the queue.
     */
    protected function runQueuedJobs()
    {
        while ($lastEvent = array_shift($this->queue)) {
            $lastEvent->__invoke();
        }
    }
}
