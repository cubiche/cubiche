<?php

/**
 * This file is part of the Cubiche/MessageBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\MessageBus\Middlewares\Locking;

use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\MessageBus\Middlewares\MiddlewareInterface;

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
     * @param mixed    $message
     * @param callable $next
     *
     * @return mixed|void
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
     *
     * @return mixed
     */
    protected function runQueuedJobs()
    {
        $returnValue = null;
        while ($lastEvent = array_shift($this->queue)) {
            $returnValue = $lastEvent->__invoke();
        }

        return $returnValue;
    }
}
