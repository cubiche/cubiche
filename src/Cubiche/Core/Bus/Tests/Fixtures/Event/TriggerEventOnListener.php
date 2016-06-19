<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Fixtures\Event;

use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;

/**
 * TriggerEventOnListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class TriggerEventOnListener
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
     * TriggerEventOnListener constructor.
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
     * @param LoginUserEvent $event
     *
     * @return bool
     */
    public function onLogin(LoginUserEvent $event)
    {
        // try to execute the same event before set the value
        $this->middleware->handle($event, $this->callback);

        $event->setEmail(sha1($event->email()));
    }
}
