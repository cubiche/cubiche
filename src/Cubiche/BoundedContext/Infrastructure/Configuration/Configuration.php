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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('microservice');

        $this->addEnvironmentSection($rootNode);
        $this->addMongoDBSection($rootNode);
        $this->addMetadataSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return ArrayNodeDefinition
     */
    protected function addEnvironmentSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('env')->defaultValue('dev')->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return ArrayNodeDefinition
     */
    protected function addMongoDBSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('mongodb')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('connections')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('read_model')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('server')->defaultValue('read_server')->end()
                                        ->scalarNode('database')->defaultValue('read_database')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('write_model')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('server')->defaultValue('write_server')->end()
                                        ->scalarNode('database')->defaultValue('write_database')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('event_store')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('server')->defaultValue('server')->end()
                                        ->scalarNode('database')->defaultValue('database')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('snapshot_store')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('server')->defaultValue('server')->end()
                                        ->scalarNode('database')->defaultValue('database')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('mappings')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($v) {
                                        return ['type' => $v];
                                    })
                                ->end()
                                ->treatNullLike([])
                                ->performNoDeepMerging()
                                ->children()
                                    ->scalarNode('type')->end()
                                    ->scalarNode('dir')->end()
                                    ->scalarNode('prefix')->end()
                                    ->scalarNode('separator')->defaultValue('/')->end()
                                ->end()
                            ->end()
                        ->end()


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
    protected function addMetadataSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('metadata')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cache_dir')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
//                        ->arrayNode('mappings')
//                            ->useAttributeAsKey('name')
//                            ->prototype('array')
//                                ->beforeNormalization()
//                                    ->ifString()
//                                    ->then(function ($v) {
//                                        return ['type' => $v];
//                                    })
//                                ->end()
//                                ->treatNullLike([])
//                                ->performNoDeepMerging()
//                                ->children()
//                                    ->scalarNode('type')->end()
//                                    ->scalarNode('dir')->end()
//                                    ->scalarNode('prefix')->end()
//                                    ->scalarNode('separator')->defaultValue('.')->end()
//                                ->end()
//                            ->end()
//                        ->end()

                    ->end()
                ->end()
            ->end()
        ;
    }
}
