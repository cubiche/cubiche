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
 * RegisterEventListenerPass class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RegisterEventListenerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->registerListeners($container);
        $this->registerSubscribers($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerListeners(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.domain.event_listener');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.event_dispatcher')) {
            $eventDispatcher = $container->getDefinition('cubiche.event_dispatcher');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    $eventDispatcher->addMethodCall(
                        'addListener',
                        array($attributes['event'], new Reference($id), $attributes['priority'])
                    );
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerSubscribers(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.domain.event_subscriber');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.event_dispatcher')) {
            $eventDispatcher = $container->getDefinition('cubiche.event_dispatcher');

            foreach ($taggedServices as $id => $attribute) {
                $eventDispatcher->addMethodCall('addSubscriber', array(new Reference($id)));
            }
        }
    }
}
