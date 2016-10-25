<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Cqrs\Tests\Fixtures\Command;

use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;

/**
 * TriggerCommandOnHandle class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class TriggerCommandOnHandle
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
        $this->middleware->handle($command, $this->callback);

        $command->setPassword(sha1($command->password()));

        return $command->password();
    }
}
