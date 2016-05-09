<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Middlewares\Handler\Resolver\CommandName;

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\CommandName\CommandNamedResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Command\LoginUserCommand;
use Cubiche\Core\Bus\Tests\Fixtures\Command\LogoutUserCommand;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * CommandNamedResolverTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandNamedResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new CommandNamedResolver())
            ->when($result = $resolver->resolve(new LogoutUserCommand('ivan@cubiche.com')))
            ->then()
                ->string($result)
                    ->isEqualTo('logout_user')
        ;

        $this
            ->given($resolver = new CommandNamedResolver())
            ->then()
                ->exception(function () use ($resolver) {
                    $resolver->resolve(new LoginUserCommand('ivan@cubiche.com', 'plainpassword'));
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
