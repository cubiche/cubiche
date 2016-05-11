<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Tests\Units\Middlewares\Handler\Resolver\HandlerMethodName;

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithObjectNameResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Command\LoginUserCommand;
use Cubiche\Core\Bus\Tests\Fixtures\Query\PublishedPostsQuery;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * MethodWithObjectNameResolverTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodWithObjectNameResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new MethodWithObjectNameResolver())
            ->when($result = $resolver->resolve(new LoginUserCommand('ivan@cubiche.com', 'plainpassword')))
            ->then()
                ->string($result)
                    ->isEqualTo('loginUserCommand')
        ;

        $this
            ->given($resolver = new MethodWithObjectNameResolver())
            ->when($result = $resolver->resolve(new PublishedPostsQuery(new \DateTime())))
            ->then()
                ->string($result)
                    ->isEqualTo('publishedPostsQuery')
        ;
    }
}
