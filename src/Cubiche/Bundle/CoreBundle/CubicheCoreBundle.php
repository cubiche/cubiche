<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Bundle\CoreBundle;

use Cubiche\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterBusHandlerPass;
use Cubiche\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterBusMiddlewarePass;
use Cubiche\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterEventListenerPass;
use Cubiche\Core\Validator\Validator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * CubicheCoreBundle class.
 *
 * @author Ivan Suárez Jerez <ivan@howaboutsales.com>
 */
class CubicheCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterBusHandlerPass());
        $container->addCompilerPass(new RegisterBusMiddlewarePass());
        $container->addCompilerPass(new RegisterEventListenerPass());
    }

    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        // force init critical services
        $this->container->get('cubiche.domain.event_publisher');

        Validator::registerValidator('Cubiche\\Domain\\Geolocation\\Validation\\Rules', true);
        Validator::registerValidator('Cubiche\\Domain\\Identity\\Validation\\Rules', true);
        Validator::registerValidator('Cubiche\\Domain\\Locale\\Validation\\Rules', true);
    }
}
