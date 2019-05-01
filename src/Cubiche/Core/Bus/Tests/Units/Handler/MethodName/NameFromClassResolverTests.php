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

use Cubiche\Core\Bus\Handler\MethodName\NameFromClassResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;
use Cubiche\Core\Bus\Tests\Units\TestCase;
use Cubiche\Core\Bus\Tests\Fixtures\Query\PublishedPostsQuery;

/**
 * NameFromClassResolverTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NameFromClassResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new NameFromClassResolver())
            ->when($result = $resolver->resolve(new LoginUserMessage('ivan@cubiche.com')))
            ->then()
                ->string($result)
                    ->isEqualTo('loginUserMessage')
        ;

        $this
            ->given($resolver = new NameFromClassResolver())
            ->when($result = $resolver->resolve(new PublishedPostsQuery(new \DateTime())))
            ->then()
                ->string($result)
                    ->isEqualTo('publishedPostsQuery')
        ;
    }
}
