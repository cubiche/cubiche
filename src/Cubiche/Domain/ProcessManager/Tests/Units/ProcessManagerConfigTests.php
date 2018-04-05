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

use Cubiche\Domain\ProcessManager\ProcessManagerConfig;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager\OrderProcessStates;

/**
 * ProcessManagerConfigTests class.
 *
 * Generated by TestGenerator on 2018-02-22 at 09:42:04.
 */
class ProcessManagerConfigTests extends TestCase
{
    protected function createProcessManagerConfig()
    {
        return new ProcessManagerConfig();
    }

    /**
     * Test addStates method.
     */
    public function testAddStates()
    {
        $this
            ->given($config = $this->createProcessManagerConfig())
            ->then()
                ->array($config->toArray())
                    ->child['states'](function ($states) {
                        $states->isEmpty();
                    })
                ->and()
                ->when(
                    $config->addStates(
                        array(
                            OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION,
                            OrderProcessStates::AWAITING_PAYMENT,
                            OrderProcessStates::REJECTED,
                            OrderProcessStates::COMPLETED,
                            OrderProcessStates::EXPIRED,
                        )
                    )
                )
                ->then()
                    ->array($config->toArray())
                        ->child['states'](function ($states) {
                            $states
                                ->isEqualTo(
                                    array(
                                        OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION,
                                        OrderProcessStates::AWAITING_PAYMENT,
                                        OrderProcessStates::REJECTED,
                                        OrderProcessStates::COMPLETED,
                                        OrderProcessStates::EXPIRED,
                                    )
                                )
                            ;
                        })
        ;
    }

    /**
     * Test addTransitions method.
     */
    public function testAddTransitions()
    {
        $this
            ->given($config = $this->createProcessManagerConfig())
            ->then()
                ->array($config->toArray())
                    ->child['transitions'](function ($states) {
                        $states->isEmpty();
                    })
                ->and()
                ->when(
                    $config->addTransitions(
                        array(
                            'accept' => array(
                                'from' => array(OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION),
                                'to' => OrderProcessStates::AWAITING_PAYMENT,
                            ),
                            'reject' => array(
                                'from' => array(OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION),
                                'to' => OrderProcessStates::REJECTED,
                            ),
                            'complete' => array(
                                'from' => array(OrderProcessStates::AWAITING_PAYMENT),
                                'to' => OrderProcessStates::COMPLETED,
                            ),
                            'expire' => array(
                                'from' => array(OrderProcessStates::AWAITING_PAYMENT),
                                'to' => OrderProcessStates::EXPIRED,
                            ),
                        )
                    )
                )
                ->then()
                    ->array($config->toArray())
                        ->child['transitions'](function ($states) {
                            $states
                                ->isEqualTo(
                                    array(
                                        'accept' => array(
                                            'from' => array(OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION),
                                            'to' => OrderProcessStates::AWAITING_PAYMENT,
                                        ),
                                        'reject' => array(
                                            'from' => array(OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION),
                                            'to' => OrderProcessStates::REJECTED,
                                        ),
                                        'complete' => array(
                                            'from' => array(OrderProcessStates::AWAITING_PAYMENT),
                                            'to' => OrderProcessStates::COMPLETED,
                                        ),
                                        'expire' => array(
                                            'from' => array(OrderProcessStates::AWAITING_PAYMENT),
                                            'to' => OrderProcessStates::EXPIRED,
                                        ),
                                    )
                                )
                            ;
                        })
        ;
    }
}
