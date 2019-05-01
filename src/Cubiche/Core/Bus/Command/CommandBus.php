<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Command;

use Cubiche\Core\Bus\Bus;
use Cubiche\Core\Bus\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Handler\MethodName\ShortNameFromClassResolver;
use Cubiche\Core\Bus\Handler\MethodName\ShortNameFromClassWithSuffixResolver;
use Cubiche\Core\Bus\Handler\Resolver\MessageHandlerResolver;
use Cubiche\Core\Bus\Message\Resolver\ClassBasedNameResolver;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\LockingMiddleware;
use Cubiche\Core\Bus\Middlewares\ValidatorMiddleware;
use Cubiche\Core\Bus\Middlewares\CommandHandlerMiddleware;

/**
 * CommandBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBus extends Bus
{
    /**
     * @return CommandBus
     */
    public static function create(array $commandBusHandlers = [])
    {
        return new static([
            500 => new LockingMiddleware(),
            250 => new ValidatorMiddleware(new MessageHandlerResolver(
                new ClassBasedNameResolver(),
                new ShortNameFromClassWithSuffixResolver('Validator'),
                new InMemoryLocator($commandBusHandlers)
            )),
            100 => new CommandHandlerMiddleware(new MessageHandlerResolver(
                new ClassBasedNameResolver(),
                new ShortNameFromClassResolver(),
                new InMemoryLocator($commandBusHandlers)
            )),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(MessageInterface $command)
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

        parent::dispatch($command);
    }
}
