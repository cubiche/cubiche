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

use Cubiche\Core\Async\Promise\Resolver;

/**
 * Resolver Test Case class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ResolverTestCase extends ResolverInterfaceTestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->extends(Resolver::class)
        ;
    }
}
