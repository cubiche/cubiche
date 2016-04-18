<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\CommandBus;

use Cubiche\Domain\CommandBus\Exception\InvalidCommandException;
use Cubiche\Domain\CommandBus\Exception\InvalidMiddlewareException;
use Cubiche\Core\Delegate\Delegate;

/**
 * CommandBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBus
{
    /**
     * @var Delegate
     */
    protected $chainedMiddleware;

    /**
     * CommandBus constructor.
     *
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

            $next = Delegate::fromClosure(function ($command) use ($middleware, $next) {
                return $middleware->execute($command, $next);
            });
        }

        return $next;
    }
}
