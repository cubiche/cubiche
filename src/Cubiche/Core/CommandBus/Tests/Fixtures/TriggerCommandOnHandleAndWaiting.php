<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Tests\Fixtures;

use Cubiche\Core\CommandBus\Middlewares\Locking\LockingMiddleware;

/**
 * TriggerCommandOnHandleAndWaiting class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class TriggerCommandOnHandleAndWaiting
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var LockingMiddleware
     */
    protected $middleware;

    /**
     * TriggerCommandOnHandle constructor.
     *
     * @param LockingMiddleware $middleware
     * @param callable          $callback
     */
    public function __construct(LockingMiddleware $middleware, callable $callback)
    {
        $this->middleware = $middleware;
        $this->callback = $callback;
    }

    /**
     * @param LoginUserCommand $command
     *
     * @return bool
     */
    public function handle(LoginUserCommand $command)
    {
        // try to execute the same command before set the value
        $promise = $this->middleware->execute($command, $this->callback);

        $promise->then(function () use ($command) {
            $command->setPassword(sha1($command->password()));
        });

        return $command->password();
    }
}
