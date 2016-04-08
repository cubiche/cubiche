<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventBus;

use Cubiche\Domain\EventBus\Exception\InvalidMiddlewareException;
use Cubiche\Domain\Delegate\Delegate;

/**
 * EventBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventBus
{
    /**
     * @var Delegate
     */
    protected $chainedMiddleware;

    /**
     * EventBus constructor.
     *
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->chainedMiddleware = $this->chainedExecution($middlewares);
    }

    /**
     * Executes the given event and optionally returns a value.
     *
     * @param EventInterface $event
     *
     * @return mixed
     */
    public function emit(EventInterface $event)
    {
        $chainedMiddleware = $this->chainedMiddleware;

        return $chainedMiddleware($event);
    }

    /**
     * @param MiddlewareInterface[] $middlewares
     *
     * @return Delegate
     */
    private function chainedExecution(array $middlewares)
    {
        $next = Delegate::fromClosure(function () {
            // the final middleware is empty
        });

        // reverse iteration over middlewares
        while ($middleware = array_pop($middlewares)) {
            if (!$middleware instanceof MiddlewareInterface) {
                throw InvalidMiddlewareException::forMiddleware($middleware);
            }

            $next = Delegate::fromClosure(function (EventInterface $event) use ($middleware, $next) {
                return $middleware->notify($event, $next);
            });
        }

        return $next;
    }
}
