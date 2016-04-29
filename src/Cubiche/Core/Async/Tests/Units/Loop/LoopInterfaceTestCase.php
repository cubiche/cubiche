<?php

/**
 * This file is part of the Cubiche/Async component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Tests\Units\Loop;

use Cubiche\Core\Async\Loop\LoopInterface;
use Cubiche\Core\Async\Tests\Units\TestCase;

/**
 * Loop Interface Test Case class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class LoopInterfaceTestCase extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(LoopInterface::class)
        ;
    }
}
