<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Fixtures\Message;

use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;

/**
 * TriggerMessageOnListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class TriggerMessageOnListener
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
     * TriggerMessageOnListener constructor.
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
     * @param LoginUserMessage $event
     *
     * @return bool
     */
    public function onLogin(LoginUserMessage $event)
    {
        // try to execute the same event before set the value
        $this->middleware->handle($event, $this->callback);

        $event->setEmail(sha1($event->email()));
    }
}
