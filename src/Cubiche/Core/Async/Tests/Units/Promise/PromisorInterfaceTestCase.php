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

use Cubiche\Core\Async\Promise\PromiseInterface;
use Cubiche\Core\Async\Promise\PromisorInterface;
use Cubiche\Core\Async\Tests\Units\TestCase;

/**
 * Promise Interface Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class PromisorInterfaceTestCase extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(PromisorInterface::class)
        ;
    }

    /**
     * Test promise.
     */
    public function testPromise()
    {
        $this
            /* @var \Cubiche\Core\Async\Promise\PromisorInterface $promisor */
            ->given($promisor = $this->newDefaultTestedInstance())
            ->then()
                ->object($promisor->promise())
                    ->isInstanceOf(PromiseInterface::class);
    }
}
