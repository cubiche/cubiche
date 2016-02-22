<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Async\Tests\Units;

use Cubiche\Domain\Async\Deferred;
use Cubiche\Domain\Async\DeferredInterface;
use Cubiche\Domain\Tests\Units\TestCase;

class DeferredTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
            ->implements(DeferredInterface::class)
        ;
    }

    /*
     * Test defer method.
     */
    public function testDefer()
    {
        $this
            ->given($deferred = Deferred::defer())
            ->then
                ->object($deferred)
                ->isInstanceOf(Deferred::class)
                ->isNotIdenticalTo(Deferred::defer())
        ;
    }

//    /*
//     * Test resolve method.
//     */
//    public function testThenSucceed()
//    {
//        $this
//            ->given($deferred = Deferred::defer())
//            ->then
//            ->object($deferred)
//            ->isInstanceOf(Deferred::class)
//        ;
//    }
}
