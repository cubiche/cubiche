<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Middlewares;

use Cubiche\Core\Bus\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Handler\Resolver\MessageHandlerResolver;
use Cubiche\Core\Bus\Middlewares\MessageHandlerMiddleware;
use Cubiche\Core\Bus\Handler\MethodName\ShortNameFromClassResolver;
use Cubiche\Core\Bus\Message\Resolver\ClassBasedNameResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessageListener;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * MessageHandlerMiddlewareTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessageHandlerMiddlewareTests extends TestCase
{
    /**
     * Test handle method.
     */
    public function testHandle()
    {
        $this
            ->given(
                $resolver = new MessageHandlerResolver(
                    new ClassBasedNameResolver(),
                    new ShortNameFromClassResolver(),
                    new InMemoryLocator([LoginUserMessage::class => new LoginUserMessageListener()])
                )
            )
            ->and($middleware = new MessageHandlerMiddleware($resolver))
            ->and($command = new LoginUserMessage('ivan@cubiche.com', 'plainpassword'))
            ->and($callable = function (LoginUserMessage $command) {
                $command->setEmail('info@cubiche.org');
            })
            ->when($middleware->handle($command, $callable))
            ->then()
                ->string($command->email())
                    ->isEqualTo('info@cubiche.org')
        ;
    }
}
