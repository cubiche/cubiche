<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Middlewares\Handler\Resolver\NameOfMessage;

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromMessageNamedResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LogoutUserMessage;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * FromMessageNamedResolverTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FromMessageNamedResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new FromMessageNamedResolver())
            ->when($result = $resolver->resolve(new LogoutUserMessage('ivan@cubiche.com')))
            ->then()
                ->string($result)
                    ->isEqualTo('logout_user')
        ;
    }
}
