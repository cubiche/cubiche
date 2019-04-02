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
use Cubiche\Infrastructure\Cqrs\Factory\Bus\QueryBusFactory;
use Cubiche\Infrastructure\Cqrs\Factory\HandlerClassResolverFactory;
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
            'app.query_bus.validator_handlers' => [],
            'app.query_bus' => function (ContainerInterface $container) {
                /** @var HandlerClassResolverFactory $handlerClassResolverFactory */
                $handlerClassResolverFactory = $container->get('app.bus.factory.handler_class_resolver');

                $queryHandlerResolver = $handlerClassResolverFactory->createForQuery(
                    new InMemoryLocator()
                );

                $validatorHandlerResolver = $handlerClassResolverFactory->createForQueryValidator(
                    new InMemoryLocator()
                );

                // configure handlers
                $handlers = $container->get('app.query_bus.handlers');
                foreach ($handlers as $queryName => $queryHandler) {
                    $queryHandlerResolver->addHandler($queryName, $queryHandler);
                }

                return QueryBusFactory::create($queryHandlerResolver, $validatorHandlerResolver);
            }
        ];
    }
}
