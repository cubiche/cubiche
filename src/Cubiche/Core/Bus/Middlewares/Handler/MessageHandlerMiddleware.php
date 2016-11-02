<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler;

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\ResolverInterface;
use Cubiche\Core\Bus\Middlewares\MiddlewareInterface;

/**
 * MessageHandlerMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessageHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @var ResolverInterface
     */
    protected $handlerClassResolver;

    /**
     * MessageHandlerMiddleware constructor.
     *
     * @param ResolverInterface $handlerClassResolver
     */
    public function __construct(ResolverInterface $handlerClassResolver)
    {
        $this->handlerClassResolver = $handlerClassResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        $this->ensureTypeOfMessage($message);
        $handler = $this->handlerClassResolver->resolve($message);

        $handler($message);
        $next($message);
    }

    /**
     * @return ResolverInterface
     */
    public function resolver()
    {
        return $this->handlerClassResolver;
    }

    /**
     * @param mixed $message
     *
     * @throws \InvalidArgumentException
     */
    protected function ensureTypeOfMessage($message)
    {
        if (!$message instanceof MessageInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    MessageInterface::class,
                    get_class($message)
                )
            );
        }
    }
}
