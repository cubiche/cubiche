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
     * @throws \Exception
     *
     * @return mixed|void
     */
    public function execute($command, callable $next)
    {
        $this->queue[] = Delegate::fromClosure(function () use ($command, $next) {
            return $next($command);
        });

        if ($this->isRunning) {
            return;
        }

        $this->isRunning = true;
        try {
            $returnValue = $this->runQueuedJobs();
        } catch (\Exception $e) {
            $this->isRunning = false;
            $this->queue = [];
            throw $e;
        }

        $this->isRunning = false;

        return $returnValue;
    }

    /**
     * Process any pending commands in the queue. If multiple, jobs are in the
     * queue, only the first return value is given back.
     *
     * @return mixed
     */
    protected function runQueuedJobs()
    {
        $returnValues = [];
        while ($lastCommand = array_shift($this->queue)) {
            $returnValues[] = $lastCommand->__invoke();
        }

        return array_shift($returnValues);
    }
}
