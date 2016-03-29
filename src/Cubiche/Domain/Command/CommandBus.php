<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command;

use Cubiche\Domain\Command\Exception\InvalidCommandException;
use Cubiche\Domain\Command\Exception\InvalidMiddlewareException;

/**
 * CommandBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBus
{
    /**
     * @var callable
     */
    protected $chainedMiddleware;

    /**
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->chainedMiddleware = $this->chainedExecution($middlewares);
    }

    /**
     * Executes the given command and optionally returns a value.
     *
     * @param object $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        if (!is_object($command)) {
            throw InvalidCommandException::forUnknownValue($command);
        }

        $chainedMiddleware = $this->chainedMiddleware;

        return $chainedMiddleware($command);
    }

    /**
     * @param MiddlewareInterface[] $middlewares
     *
     * @return callable
     */
    private function chainedExecution($middlewares)
    {
        $lastCallable = function () {
            // the final callable is a no-op
        };

        while ($middleware = array_pop($middlewares)) {
            if (!$middleware instanceof MiddlewareInterface) {
                throw InvalidMiddlewareException::forMiddleware($middleware);
            }

            $lastCallable = function ($command) use ($middleware, $lastCallable) {
                return $middleware->execute($command, $lastCallable);
            };
        }

        return $lastCallable;
    }
}
