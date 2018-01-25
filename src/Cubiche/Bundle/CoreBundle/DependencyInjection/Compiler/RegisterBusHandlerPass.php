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

/**
 * RegisterBusHandlerPass class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RegisterBusHandlerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->registerCommands($container);
        $this->registerQueries($container);
        $this->registerValidators($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerCommands(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.command_handler');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.command_bus.handler_resolver')) {
            $commandHandlerResolver = $container->getDefinition('cubiche.command_bus.handler_resolver');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    $commandHandlerResolver->addMethodCall('addHandler', array($attributes['class'], $id));
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerQueries(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.query_handler');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.query_bus.handler_resolver')) {
            $queryHandlerResolver = $container->getDefinition('cubiche.query_bus.handler_resolver');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    $queryHandlerResolver->addMethodCall('addHandler', array($attributes['class'], $id));
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerValidators(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.command.validator');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.command_bus.validator_handler_resolver')) {
            $validatorHandlerResolver = $container->getDefinition('cubiche.command_bus.validator_handler_resolver');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    $validatorHandlerResolver->addMethodCall('addHandler', array($attributes['class'], $id));
                }
            }
        }

        $taggedServices = $container->findTaggedServiceIds('cubiche.query.validator');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.query_bus.validator_handler_resolver')) {
            $validatorHandlerResolver = $container->getDefinition('cubiche.query_bus.validator_handler_resolver');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    $validatorHandlerResolver->addMethodCall('addHandler', array($attributes['class'], $id));
                }
            }
        }
    }
}
