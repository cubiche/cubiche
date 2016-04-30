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

use React\EventLoop\Timer\TimerInterface;

/**
 * Loop Adapter Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LoopAdapterTests extends LoopTests
{
    /**
     * Test addTimer method.
     */
    public function testAddTimer()
    {
        $this->proxyMethodTest('addTimer', array(1, $this->delegateMock()));
    }

    /**
     * Test addPeriodicTimer method.
     */
    public function testAddPeriodicTimer()
    {
        $this->proxyMethodTest('addPeriodicTimer', array(1, $this->delegateMock()));
    }

    /**
     * Test cancelTimer method.
     */
    public function testCancelTimer()
    {
        $this->proxyMethodTest('cancelTimer', array($this->newMockInstance(TimerInterface::class)));
    }

    /**
     * Test isTimerActive method.
     */
    public function testIsTimerActive()
    {
        $this->proxyMethodTest('isTimerActive', array($this->newMockInstance(TimerInterface::class)));
    }

    /**
     * Test nextTick method.
     */
    public function testNextTick()
    {
        $this->proxyMethodTest('nextTick', array($this->delegateMock()));
    }

    /**
     * Test futureTick method.
     */
    public function testFutureTick()
    {
        $this->proxyMethodTest('futureTick', array($this->delegateMock()));
    }
}
