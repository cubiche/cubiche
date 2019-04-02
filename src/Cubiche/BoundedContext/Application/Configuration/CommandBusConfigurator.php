<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application\Configuration;

use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Infrastructure\Cqrs\Factory\Bus\CommandBusFactory;
use Cubiche\Infrastructure\Cqrs\Factory\HandlerClassResolverFactory;
use Psr\Container\ContainerInterface;

/**
 * CommandBusConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBusConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.command_bus.handlers' => [],
            'app.command_bus.validator_handlers' => [],
            'app.command_bus' => function (ContainerInterface $container) {
                /** @var HandlerClassResolverFactory $handlerClassResolverFactory */
                $handlerClassResolverFactory = $container->get('app.bus.factory.handler_class_resolver');

                $commandHandlerResolver = $handlerClassResolverFactory->createForCommand(
                    new InMemoryLocator()
                );

                $validatorHandlerResolver = $handlerClassResolverFactory->createForCommandValidator(
                    new InMemoryLocator()
                );

                // configure handlers
                $handlers = $container->get('app.command_bus.handlers');
                foreach ($handlers as $commandName => $commandHandler) {
                    $commandHandlerResolver->addHandler($commandName, $commandHandler);
                }

                return CommandBusFactory::create(
                    $commandHandlerResolver,
                    $validatorHandlerResolver,
                    $container->get('app.bus.message_publisher')
                );
            }
        ];
    }
}
