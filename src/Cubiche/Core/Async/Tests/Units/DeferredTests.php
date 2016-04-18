<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Async\Tests\Units;

use Cubiche\Core\Async\Deferred;
use Cubiche\Core\Async\DeferredInterface;

/**
 * Deferred Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DeferredTests extends DeferredInterfaceTestCase
{
    /**
     * Test defer method.
     */
    public function testDefer()
    {
        $this
            ->given($deferred = Deferred::defer())
            ->then()
                ->object($deferred)
                    ->isInstanceOf(DeferredInterface::class)
                    ->isNotIdenticalTo(Deferred::defer())
        ;
    }
}
