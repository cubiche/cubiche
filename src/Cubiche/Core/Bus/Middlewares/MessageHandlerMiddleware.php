<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares;

use Cubiche\Core\Bus\Handler\Resolver\MessageHandlerResolverInterface;
use Cubiche\Core\Bus\MessageInterface;

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
    protected $messageHandlerResolver;

    /**
     * MessageHandlerMiddleware constructor.
     *
     * @param MessageHandlerResolverInterface $messageHandlerResolver
     */
    public function __construct(MessageHandlerResolverInterface $messageHandlerResolver)
    {
        $this->messageHandlerResolver = $messageHandlerResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
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

        $handler = $this->messageHandlerResolver->resolve($message);

        call_user_func($handler, $message);
        $next($message);
    }
}
