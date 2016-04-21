<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Middlewares\Locking;

use Cubiche\Core\Async\Deferred;
use Cubiche\Core\Async\Promise;
use Cubiche\Core\CommandBus\MiddlewareInterface;
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
     * Execute the given command... after other running commands are complete.
     *
     * @param object   $command
     * @param callable $next
     *
     * @return Promise
     */
    public function execute($command, callable $next)
    {
        $deferred = Deferred::defer();
        $this->queue[] = Delegate::fromClosure(function () use ($command, $next, $deferred) {
            try {
                $deferred->notify(CommandState::RECEIVED);

                $returnValue = $next($command);

                $deferred->notify(CommandState::HANDLED);
                $deferred->resolve($returnValue);
            } catch (\Exception $e) {
                $deferred->notify(CommandState::FAILED);

                $deferred->reject($e);
            }
        });

        if ($this->isRunning) {
            return $deferred->promise();
        }

        $this->isRunning = true;
        $this->runQueuedJobs();
        $this->isRunning = false;

        return $deferred->promise();
    }

    /**
     * Process any pending commands in the queue.
     */
    protected function runQueuedJobs()
    {
        while ($lastCommand = array_shift($this->queue)) {
            $lastCommand->__invoke();
        }
    }
}
