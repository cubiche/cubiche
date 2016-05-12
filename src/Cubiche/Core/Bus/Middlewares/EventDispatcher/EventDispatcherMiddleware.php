<?php

/**
 * This file is part of the Cubiche/Bus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\EventDispatcher;

use Cubiche\Core\EventDispatcher\EventDispatcherInterface;
use Cubiche\Core\Bus\Event\EventInterface;
use Cubiche\Core\Bus\Middlewares\MiddlewareInterface;

/**
 * EventDispatcherMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventDispatcherMiddleware implements MiddlewareInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * EventDispatcherMiddleware constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($event, callable $next)
    {
        if (!$event instanceof EventInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    EventInterface::class,
                    get_class($event)
                )
            );
        }

        $this->dispatcher->dispatch($event);

        $next($event);
    }

    /**
     * @return EventDispatcherInterface
     */
    public function dispatcher()
    {
        return $this->dispatcher;
    }
}
