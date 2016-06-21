<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector\Tests\Units;

use Cubiche\Core\Selector\SelectorInterface;

/**
 * Selector Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SelectorTestCase extends SelectorInterfaceTestCase
{
    /**
     * Test select.
     */
    public function testSelect()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selector $selector */
            ->given($selector = $this->newDefaultTestedInstance())
            ->then()
            ->when($selected = $selector->select($this->newDefaultTestedInstance()))
                ->object($selected)
                    ->isInstanceOf(SelectorInterface::class)
        ;
    }
}
