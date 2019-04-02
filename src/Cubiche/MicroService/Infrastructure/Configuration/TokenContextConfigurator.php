<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\Configuration;

use Cubiche\BoundedContext\Application\Configuration\ConfiguratorInterface;
use Cubiche\MicroService\Application\Services\TokenManager;
use Cubiche\MicroService\Infrastructure\Services\TokenContext;
use Psr\Container\ContainerInterface;

/**
 * TokenContextConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TokenContextConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.acl.token_manager' => function (ContainerInterface $container) {
                return new TokenManager(
                    $container->get('app.acl.parameter.api_domain'),
                    $container->get('app.acl.parameter.domain'),
                    realpath($container->get('app.acl.parameter.public_key')),
                    realpath($container->get('app.acl.parameter.private_key'))
                );
            },
            'app.acl.token_context' => function (ContainerInterface $container) {
                return new TokenContext($container->get('app.acl.token_manager'));
            }
        ];
    }
}
