<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Tests\Units\Handler\MethodName;

use Cubiche\Core\Bus\Handler\MethodName\DefaultResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * DefaultResolver class.
 *
 * Generated by TestGenerator on 2016-04-07 at 15:40:41.
 */
class DefaultResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new DefaultResolver())
            ->when($result = $resolver->resolve(new LoginUserMessage('ivan@cubiche.com')))
            ->then()
                ->string($result)
                    ->isEqualTo('handle')
        ;
    }
}