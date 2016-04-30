<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Async\Tests\Units\Promise;

use Cubiche\Core\Async\Promise\RejectionException;

/**
 * Done Resolver Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DoneResolverTests extends ResolverInterfaceTestCase
{
    /**
     * Test resolve.
     */
    public function testResolve()
    {
        $this
            ->given(
                $resolver = $this->newDefaultTestedInstance()
            )
            ->when($result = $resolver->resolve())
            ->then()
                ->variable($result)
                    ->isNull()
        ;
    }

    /**
     * Test reject.
     */
    public function testReject()
    {
        $this
            ->given(
                $resolver = $this->newDefaultTestedInstance()
            )
            ->exception(function () use ($resolver) {
                $resolver->reject();
            })
            ->isInstanceof(RejectionException::class)
        ;
    }

    /**
     * Test notify.
     */
    public function testNotify()
    {
        $this
            ->given(
                $resolver = $this->newDefaultTestedInstance()
            )
            ->when($result = $resolver->notify())
            ->then()
                ->variable($result)
                    ->isNull()
        ;
    }
}
