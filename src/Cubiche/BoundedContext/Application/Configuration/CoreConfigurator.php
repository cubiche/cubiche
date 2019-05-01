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

use Cubiche\Core\Bus\Handler\MethodName\ShortNameFromClassResolver;
use Cubiche\Core\Bus\Handler\MethodName\ShortNameFromClassWithSuffixResolver;
use Cubiche\Core\Bus\Message\Publisher\MessagePublisher;
use Cubiche\Core\Bus\Message\Resolver\ClassBasedNameResolver;
use Cubiche\Domain\EventSourcing\Factory\AggregateRepositoryFactory;
use Cubiche\Infrastructure\Bus\Factory\MessageHandlerResolverFactory;
use Psr\Container\ContainerInterface;

/**
 * CoreConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CoreConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.repository.factory.event_sourced_aggregate' => function (ContainerInterface $container) {
                return new AggregateRepositoryFactory(
                    $container->get('app.event_store'),
                    $container->get('app.bus.message_publisher')
                );
            },
            'app.bus.message_publisher' => function (ContainerInterface $container) {
                return new MessagePublisher($container->get('app.event_bus'));
            },
            'app.bus.message_name_resolver' => new ClassBasedNameResolver(),
            'app.bus.handler_method_name_resolver' => new ShortNameFromClassResolver(),
            'app.bus.handler_validator_method_name_resolver' => function (ContainerInterface $container) {
                return new ShortNameFromClassWithSuffixResolver('Validator');
            },
        ];
    }
}
