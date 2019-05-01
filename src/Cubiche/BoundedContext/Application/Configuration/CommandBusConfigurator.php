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

use Cubiche\Core\Bus\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Handler\Resolver\MessageHandlerResolver;
use Cubiche\Core\Bus\Middlewares\LockingMiddleware;
use Cubiche\Core\Bus\Middlewares\MessagePublisherMiddleware;
use Cubiche\Core\Bus\Middlewares\ValidatorMiddleware;
use Cubiche\Core\Bus\Command\CommandBus;
use Cubiche\Core\Bus\Middlewares\CommandHandlerMiddleware;
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
            'app.command_bus.handlers_locator' => function (ContainerInterface $container) {
                return new InMemoryLocator($container->get('app.command_bus.handlers'));
            },
            'app.command_bus.middlewares' => function (ContainerInterface $container) {
                $commandHandlerResolver = new MessageHandlerResolver(
                    $container->get('app.bus.message_name_resolver'),
                    $container->get('app.bus.handler_method_name_resolver'),
                    $container->get('app.command_bus.handlers_locator')
                );

                $commandHandlerValidatorResolver = new MessageHandlerResolver(
                    $container->get('app.bus.message_name_resolver'),
                    $container->get('app.bus.handler_validator_method_name_resolver'),
                    $container->get('app.command_bus.handlers_locator')
                );

                return [
                    400 => new LockingMiddleware(),
                    350 => new ValidatorMiddleware($commandHandlerValidatorResolver),
                    300 => new CommandHandlerMiddleware($commandHandlerResolver),
                    250 => new MessagePublisherMiddleware($container->get('app.bus.message_publisher'))
                ];
            },
            'app.command_bus' => function (ContainerInterface $container) {
                return new CommandBus($container->get('app.command_bus.middlewares'));
            }
        ];
    }
}
