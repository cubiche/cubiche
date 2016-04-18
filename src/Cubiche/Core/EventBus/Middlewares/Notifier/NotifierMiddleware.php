<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\EventBus\Middlewares\Notifier;

use Cubiche\Core\EventBus\Notifier;
use Cubiche\Core\EventBus\EventInterface;
use Cubiche\Core\EventBus\MiddlewareInterface;

/**
 * NotifierMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NotifierMiddleware implements MiddlewareInterface
{
    /**
     * @var Notifier
     */
    protected $notifier;

    /**
     * NotifierMiddleware constructor.
     *
     * @param Notifier $notifier
     */
    public function __construct(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(EventInterface $event, callable $next)
    {
        $this->notifier->notify($event);

        $next($event);
    }

    /**
     * @return Notifier
     */
    public function notifier()
    {
        return $this->notifier;
    }
}
