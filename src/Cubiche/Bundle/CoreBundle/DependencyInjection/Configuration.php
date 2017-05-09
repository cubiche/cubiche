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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cubiche_core');

        $this->addEventStoreSection($rootNode);
        $this->addSnapshotStoreSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return ArrayNodeDefinition
     */
    protected function addEventStoreSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('event_store')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('document_manager')->defaultValue('event_store')->end()
                        ->scalarNode('database')->defaultValue('event_store_database')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return ArrayNodeDefinition
     */
    protected function addSnapshotStoreSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('snapshot_store')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('document_manager')->defaultValue('snapshot_store')->end()
                        ->scalarNode('database')->defaultValue('snapshot_store_database')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
