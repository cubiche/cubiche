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
use Cubiche\Core\Bus\Middlewares\ValidatorMiddleware;
use Cubiche\Core\Bus\Middlewares\QueryHandlerMiddleware;
use Cubiche\Core\Bus\Query\QueryBus;
use Psr\Container\ContainerInterface;

/**
 * QueryBusConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class QueryBusConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.query_bus.handlers' => [],
            'app.query_bus.handlers_locator' => function (ContainerInterface $container) {
                return new InMemoryLocator($container->get('app.query_bus.handlers'));
            },
            'app.query_bus.middlewares' => function (ContainerInterface $container) {
                $queryHandlerResolver = new MessageHandlerResolver(
                    $container->get('app.bus.message_name_resolver'),
                    $container->get('app.bus.handler_method_name_resolver'),
                    $container->get('app.query_bus.handlers_locator')
                );

                $queryHandlerValidatorResolver = new MessageHandlerResolver(
                    $container->get('app.bus.message_name_resolver'),
                    $container->get('app.bus.handler_validator_method_name_resolver'),
                    $container->get('app.query_bus.handlers_locator')
                );

                return [
                    350 => new ValidatorMiddleware($queryHandlerValidatorResolver),
                    300 => new QueryHandlerMiddleware($queryHandlerResolver),
                ];
            },
            'app.query_bus' => function (ContainerInterface $container) {
                return new QueryBus($container->get('app.query_bus.middlewares'));
            }
        ];
    }
}
