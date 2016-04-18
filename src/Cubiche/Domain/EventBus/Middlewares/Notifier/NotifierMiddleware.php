<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventBus\Middlewares\Notifier;

use Cubiche\Domain\EventBus\Notifier;
use Cubiche\Domain\EventBus\EventInterface;
use Cubiche\Domain\EventBus\MiddlewareInterface;

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
