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
     * @return mixed|void
     *
     * @throws \Exception
     */
    public function execute($command, callable $next)
    {
        $this->queue[] = Delegate::fromClosure(function () use ($command, $next) {
            $next($command);
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
     * Process any pending command in the queue.
     */
    protected function runQueuedJobs()
    {
        while ($lastCommand = array_shift($this->queue)) {
            $lastCommand->__invoke();
        }
    }
}
