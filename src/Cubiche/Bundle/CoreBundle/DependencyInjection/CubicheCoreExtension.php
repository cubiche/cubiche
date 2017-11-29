<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * CubicheCoreExtension class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CubicheCoreExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function configure(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->processEventStoreConfiguration($config['event_store'], $container);
        $this->processSnapshotStoreConfiguration($config['snapshot_store'], $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function processEventStoreConfiguration(array $config, ContainerBuilder $container)
    {
        $serviceId = 'cubiche.mongodb.odm.event_store_document_manager';
        $documentManagerId = sprintf(
            'doctrine_mongodb.odm.%s_document_manager',
            $config['document_manager']
        );

        $container->setAlias($serviceId, $documentManagerId);

        $container
            ->setParameter('cubiche.mongodb.event_store.database', $config['database'])
        ;
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function processSnapshotStoreConfiguration(array $config, ContainerBuilder $container)
    {
        $serviceId = 'cubiche.mongodb.odm.snapshot_store_document_manager';
        $documentManagerId = sprintf(
            'doctrine_mongodb.odm.%s_document_manager',
            $config['document_manager']
        );

        $container->setAlias($serviceId, $documentManagerId);
        $container
            ->setParameter('cubiche.mongodb.snapshot_store.database', $config['database'])
        ;
    }
}
