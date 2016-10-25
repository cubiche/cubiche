<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Cqrs\Tests\Units\Middlewares\Handler\Resolver\NameOfCommand;

use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfCommand\FromCommandNamedResolver;
use Cubiche\Core\Cqrs\Tests\Fixtures\Command\LoginUserCommand;
use Cubiche\Core\Cqrs\Tests\Fixtures\Command\LogoutUserCommand;
use Cubiche\Core\Cqrs\Tests\Units\TestCase;

/**
 * FromCommandNamedResolverTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FromCommandNamedResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new FromCommandNamedResolver())
            ->when($result = $resolver->resolve(new LogoutUserCommand('ivan@cubiche.com')))
            ->then()
                ->string($result)
                    ->isEqualTo('logout_user')
        ;

        $this
            ->given($resolver = new FromCommandNamedResolver())
            ->then()
                ->exception(function () use ($resolver) {
                    $resolver->resolve(new LoginUserCommand('ivan@cubiche.com', 'plainpassword'));
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
