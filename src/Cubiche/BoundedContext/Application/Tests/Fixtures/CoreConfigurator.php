<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application\Tests\Fixtures;

use Cubiche\BoundedContext\Application\Configuration\ConfiguratorInterface;
use Cubiche\Domain\EventSourcing\EventStore\InMemoryEventStore;
use Cubiche\Domain\Repository\Factory\InMemoryAggregateRepositoryFactory;
use Cubiche\Domain\Repository\Factory\InMemoryQueryRepositoryFactory;
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
            'app.repository.factory.aggregate'  => function (ContainerInterface $container) {
                return new InMemoryAggregateRepositoryFactory($container->get('app.bus.message_publisher'));
            },
            'app.repository.factory.model' => function () {
                return new InMemoryQueryRepositoryFactory();
            },
            'app.event_store' => function () {
                return new InMemoryEventStore();
            },
        ];
    }
}
