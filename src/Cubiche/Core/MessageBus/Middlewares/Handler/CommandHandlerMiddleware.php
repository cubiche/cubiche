<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\MessageBus\Middlewares\CommandHandler;

use Cubiche\Core\MessageBus\Command\CommandInterface;
use Cubiche\Core\MessageBus\Middlewares\Handler\Resolver\HandlerClass\ResolverInterface;
use Cubiche\Core\MessageBus\Middlewares\MiddlewareInterface;

/**
 * CommandHandlerMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @var ResolverInterface
     */
    protected $handlerResolver;

    /**
     * CommandHandlerMiddleware constructor.
     *
     * @param ResolverInterface $handlerResolver
     */
    public function __construct(ResolverInterface $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($command, callable $next)
    {
        if (!$command instanceof CommandInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    CommandInterface::class,
                    get_class($command)
                )
            );
        }

        $handler = $this->handlerResolver->resolve($command);

        $handler($command);
        $next($command);
    }
}
