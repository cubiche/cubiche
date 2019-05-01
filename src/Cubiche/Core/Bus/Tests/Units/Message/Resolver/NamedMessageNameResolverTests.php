<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Message\Resolver;

use Cubiche\Core\Bus\Message\Resolver\NamedMessageNameResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LogoutUserMessage;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * NamedMessageNameResolverTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NamedMessageNameResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new NamedMessageNameResolver())
            ->when($result = $resolver->resolve(new LogoutUserMessage('ivan@cubiche.com')))
            ->then()
                ->string($result)
                    ->isEqualTo('logout_user')
                ->exception(function() use ($resolver) {
                    $resolver->resolve(new LoginUserMessage('ivan@cubiche.com'));
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
