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

use Cubiche\Core\Bus\Exception\InvalidMiddlewareException;
use Cubiche\Core\Bus\Middlewares\MiddlewareInterface;
use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\Collections\SortedArrayCollection;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ReverseComparator;
use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Specification\Criteria;

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
        $this->middlewares = new SortedArrayCollection([], new ReverseComparator(new Comparator()));
        foreach ($middlewares as $priority => $middleware) {
            if (!$middleware instanceof MiddlewareInterface) {
                throw InvalidMiddlewareException::forUnknownValue($middleware);
            }

            $this->addMiddleware($middleware, $priority);
        }
    }

    /**
     * Adds a middleware to the middleware list. The higher priority value, the earlier a middleware
     * will be triggered in the chain (defaults to 0).
     *
     * @param MiddlewareInterface $middleware
     * @param int                 $priority
     */
    public function addMiddleware(MiddlewareInterface $middleware, $priority = 0)
    {
        if (!$this->middlewares->containsKey($priority)) {
            $this->middlewares->set($priority, new ArrayCollection());
        }

        /** @var ArrayCollection $middlewares */
        $middlewares = $this->middlewares->get($priority);
        if ($middlewares->findOne(Criteria::eq($middleware)) === null) {
            $middlewares->add($middleware);
        }
    }

    /**
     * Add a middleware before a given middleware.
     *
     * @param MiddlewareInterface $middleware
     * @param MiddlewareInterface $target
     *
     * @throws \InvalidArgumentException
     */
    public function addMiddlewareBefore(MiddlewareInterface $middleware, MiddlewareInterface $target)
    {
        $priority = $this->middlewarePriority($target);
        if ($priority === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    'There is not a middleware of type %s registered.',
                    get_class($target)
                )
            );
        }

        $this->addMiddleware($middleware, $priority + 1);
    }

    /**
     * Add a middleware before a given middleware.
     *
     * @param MiddlewareInterface $middleware
     * @param MiddlewareInterface $target
     *
     * @throws \InvalidArgumentException
     */
    public function addMiddlewareAfter(MiddlewareInterface $middleware, MiddlewareInterface $target)
    {
        $priority = $this->middlewarePriority($target);
        if ($priority === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    'There is not a middleware of type %s registered.',
                    get_class($target)
                )
            );
        }

        $this->addMiddleware($middleware, $priority - 1);
    }

    /**
     * @param MiddlewareInterface $middleware
     *
     * @return int|null
     */
    protected function middlewarePriority(MiddlewareInterface $middleware)
    {
        /** @var ArrayCollection $collection */
        foreach ($this->middlewares as $priority => $collection) {
            $targetMiddleware = $collection->findOne(Criteria::eq($middleware));
            if ($targetMiddleware !== null) {
                return $priority;
            }
        }

        return;
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
        $middlewares = [];
        foreach ($this->middlewares as $priority => $collection) {
            foreach ($collection as $middleware) {
                $middlewares[] = $middleware;
            }
        }

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
