<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Infrastructure\Configuration;

use Cubiche\BoundedContext\Application\Configuration\ConfiguratorInterface;
use Cubiche\Infrastructure\Repository\MongoDB\Factory\AggregateRepositoryFactory;
use Cubiche\Infrastructure\Repository\MongoDB\Factory\DocumentDataSourceFactory;
use Cubiche\Infrastructure\Repository\MongoDB\Factory\DocumentQueryRepositoryFactory;
use Cubiche\Infrastructure\Repository\MongoDB\Factory\DocumentRepositoryFactory;
use Cubiche\Infrastructure\Repository\MongoDB\Visitor\ComparatorVisitorFactory;
use Cubiche\Infrastructure\Repository\MongoDB\Visitor\SpecificationVisitorFactory;
use Psr\Container\ContainerInterface;

/**
 * MongoDBRepositoryConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBRepositoryConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.mongodb.repository.factory.comparator_visitor' => function() {
                return new ComparatorVisitorFactory();
            },
            'app.mongodb.repository.factory.specification_visitor' => function() {
                return new SpecificationVisitorFactory();
            },
            'app.mongodb.repository.factory.document_datasource' => function(ContainerInterface $container) {
                return new DocumentDataSourceFactory(
                    $container->get('app.mongodb.read_model_document_manager'),
                    $container->get('app.mongodb.repository.factory.specification_visitor'),
                    $container->get('app.mongodb.repository.factory.comparator_visitor')
                );
            },
            'app.mongodb.repository.factory.aggregate' => function(ContainerInterface $container) {
                return new AggregateRepositoryFactory(
                    $container->get('app.mongodb.write_model_document_manager'),
                    $container->get('app.bus.message_publisher')
                );
            },
            'app.mongodb.repository.factory.document' => function(ContainerInterface $container) {
                return new DocumentRepositoryFactory(
                    $container->get('app.mongodb.read_model_document_manager')
                );
            },
            'app.mongodb.repository.factory.document_query' => function(ContainerInterface $container) {
                return new DocumentQueryRepositoryFactory(
                    $container->get('app.mongodb.read_model_document_manager'),
                    $container->get('app.mongodb.repository.factory.document_datasource')
                );
            }
        ];
    }
}
