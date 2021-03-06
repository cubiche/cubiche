<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Units;

use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\OrderId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager\OrderProcessState;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager\OrderProcessStates;

/**
 * ProcessManagerStateTests class.
 *
 * Generated by TestGenerator on 2018-02-22 at 09:42:04.
 */
class ProcessManagerStateTests extends TestCase
{
    /**
     * @return OrderProcessState
     */
    protected function createProcessManagerState()
    {
        return new OrderProcessState(OrderId::next(), ConferenceId::next());
    }

    /**
     * Test GetState method.
     */
    public function testGetState()
    {
        $this
            ->given($orderProcessState = $this->createProcessManagerState())
            ->then()
                ->object($orderProcessState->id())
                    ->isInstanceOf(OrderId::class)
                ->string($orderProcessState->getState()->toNative())
                    ->isEqualTo(OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION)
        ;
    }

    /**
     * Test SetState method.
     */
    public function testSetState()
    {
        $this
            ->given($orderProcessState = $this->createProcessManagerState())
            ->when($orderProcessState->setState(OrderProcessStates::COMPLETED))
            ->then()
                ->string($orderProcessState->getState()->toNative())
                    ->isEqualTo(OrderProcessStates::COMPLETED)
        ;
    }
}
