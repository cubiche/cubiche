<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus;

use Cubiche\Core\Collections\SortedArrayCollection;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Bus\Exception\InvalidMiddlewareException;
use Cubiche\Core\Bus\Middlewares\MiddlewareInterface;

/**
 * Bus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Bus implements BusInterface
{
    /**
     * @var SortedArrayCollection
     */
    protected $middlewares;

    /**
     * Bus constructor.
     *
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares = array())
    {
        $this->middlewares = new SortedArrayCollection([], new Comparator());
        foreach ($middlewares as $priority => $middleware) {
            if (!$middleware instanceof MiddlewareInterface) {
                throw InvalidMiddlewareException::forUnknownValue($middleware);
            }

            $this->addMiddleware($middleware, $priority);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     * @param int                 $priority
     *
     * @throws \InvalidArgumentException
     */
    public function addMiddleware(MiddlewareInterface $middleware, $priority = 0)
    {
        if ($this->middlewares->containsKey($priority)) {
            throw new \InvalidArgumentException(
                sprintf('There is another middleware with the same priority %s', $priority)
            );
        }

        $this->middlewares->set($priority, $middleware);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(MessageInterface $message)
    {
        $chainedMiddleware = $this->chainedExecution();

        return $chainedMiddleware($message);
    }

    /**
     * @return Delegate
     */
    private function chainedExecution()
    {
        $middlewares = $this->middlewares->toArray();
        $next = Delegate::fromClosure(function ($message) {
            // the final middleware return the same message
            return $message;
        });

        // reverse iteration over middlewares
        /** @var MiddlewareInterface $middleware */
        while ($middleware = array_pop($middlewares)) {
            $next = Delegate::fromClosure(function ($message) use ($middleware, $next) {
                return $middleware->handle($message, $next);
            });
        }

        return $next;
    }
}
