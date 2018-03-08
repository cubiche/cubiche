<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * RegisterBusMiddlewarePass class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RegisterBusMiddlewarePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->registerCommands($container);
        $this->registerQueries($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerCommands(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.command_bus.middleware');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.command_bus')) {
            $commandBus = $container->getDefinition('cubiche.command_bus');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    if (!isset($attributes['priority'])) {
                        throw new \InvalidArgumentException('The property `priority` is required.');
                    }

                    $commandBus->addMethodCall('addMiddleware', array(new Reference($id), $attributes['priority']));
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerQueries(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.query_bus.middleware');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.query_bus')) {
            $queryBus = $container->getDefinition('cubiche.query_bus');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    if (!isset($attributes['priority'])) {
                        throw new \InvalidArgumentException('The property `priority` is required.');
                    }

                    $queryBus->addMethodCall('addMiddleware', array(new Reference($id), $attributes['priority']));
                }
            }
        }
    }
}
