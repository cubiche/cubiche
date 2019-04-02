<?php

/**
 * This file is part of the App component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Tests\Fixtures;

use Cubiche\BoundedContext\Application\Tests\Fixtures\CoreConfigurator as BaseCoreConfigurator;
use Cubiche\MicroService\Application\Services\TokenManager;
use Psr\Container\ContainerInterface;

/**
 * CoreConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CoreConfigurator extends BaseCoreConfigurator
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return array_merge(
            parent::configuration(),
            [
                'app.acl.token_manager' => function () {
                    return new TokenManager(
                        'api.example.com',
                        'example.com',
                        realpath(__DIR__.'/../Fixtures/cert/jwt-rsa-public.pem'),
                        realpath(__DIR__.'/../Fixtures/cert/jwt-rsa-private.key')
                    );
                },
                'app.acl.token_context' => function (ContainerInterface $container) {
                    /** @var TokenManager $tokenManager */
                    $tokenManager = $container->get('app.acl.token_manager');
                    $tokenContext = new TokenContext($tokenManager);

                    $jwt = $tokenManager->encode(
                        'f3623738-c38e-4189-8f2c-8896f4e524ec',
                        'ivan@cubiche.com',
                        array('app.order', 'app.sales')
                    );

                    $tokenContext->setJWT($jwt);

                    return $tokenContext;
                }
            ]
        );
    }
}
