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

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * ConfigureDoctrinePass class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ConfigureDoctrinePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->changeClassMetadataFactory($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function changeClassMetadataFactory(ContainerBuilder $container)
    {
        $configuration = $container->getDefinition('doctrine_mongodb.odm.default_configuration');
        $configuration->addMethodCall('setClassMetadataFactoryName', array(ClassMetadataFactory::class));
    }
}
