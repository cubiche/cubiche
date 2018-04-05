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
 * RegisterSerializerHandlerPass class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RegisterSerializerHandlerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->registerHandlers($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerHandlers(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.serializer_handler');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.serializer.handler_manager')) {
            $serializerHandlerManager = $container->getDefinition('cubiche.serializer.handler_manager');

            foreach ($taggedServices as $id => $tags) {
                $serializerHandlerManager->addMethodCall('registerSubscriberHandler', array(new Reference($id)));
//                foreach ($tags as $attributes) {
//                }
            }
        }
    }
}
