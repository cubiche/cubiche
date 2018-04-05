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

use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Command\CreateConferenceCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Event\OrderHasExpired;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Event\PaymentWasReceived;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\CreateOrderCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\OrderId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager\OrderProcessState;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager\OrderProcessStates;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager\SimpleProcessManager;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\SeatsAvailability;

/**
 * ProcessManagerTests class.
 *
 * Generated by TestGenerator on 2018-02-22 at 09:42:04.
 */
class ProcessManagerTests extends TestCase
{
    /**
     * Test completed process.
     */
    public function testCompletedProcess()
    {
        $this
            ->given($conferenceId = ConferenceId::next())
            ->and($orderId = OrderId::next())
            ->and($seatsAvailabilityRepository = $this->writeRepository(SeatsAvailability::class))
            ->and($processStateRepository = $this->queryRepository(OrderProcessState::class))
            ->when(
                $this->commandBus()->dispatch(new CreateConferenceCommand(
                    $conferenceId->toNative(),
                    'DDD for everyone',
                    100
                ))
            )
            ->and($seatsAvailability = $seatsAvailabilityRepository->get($conferenceId))
            ->then()
                ->integer($seatsAvailability->availableSeats()->toNative())
                    ->isEqualTo(100)
                ->and()
                ->when(
                    $this->commandBus()->dispatch(new CreateOrderCommand(
                        $orderId->toNative(),
                        $conferenceId->toNative(),
                        10
                    ))
                )
                ->and($seatsAvailability = $seatsAvailabilityRepository->get($conferenceId))
                ->and($processState = $processStateRepository->get($orderId))
                ->then()
                    ->integer($seatsAvailability->availableSeats()->toNative())
                        ->isEqualTo(90)
                    ->string($processState->getState()->toNative())
                        ->isEqualTo(OrderProcessStates::AWAITING_PAYMENT)
                    ->and()
                    ->when(
                        DomainEventPublisher::publish(
                            new PaymentWasReceived($orderId)
                        )
                    )
                    ->and($seatsAvailability = $seatsAvailabilityRepository->get($conferenceId))
                    ->and($processState = $processStateRepository->get($orderId))
                    ->then()
                        ->integer($seatsAvailability->availableSeats()->toNative())
                            ->isEqualTo(90)
                        ->string($processState->getState()->toNative())
                            ->isEqualTo(OrderProcessStates::COMPLETED)
        ;
    }

    /**
     * Test expired process.
     */
    public function testExpiredProcess()
    {
        $this
            ->given($conferenceId = ConferenceId::next())
            ->and($orderId = OrderId::next())
            ->and($seatsAvailabilityRepository = $this->writeRepository(SeatsAvailability::class))
            ->and($processStateRepository = $this->queryRepository(OrderProcessState::class))
            ->when(
                $this->commandBus()->dispatch(new CreateConferenceCommand(
                    $conferenceId->toNative(),
                    'DDD for everyone',
                    100
                ))
            )
            ->and($seatsAvailability = $seatsAvailabilityRepository->get($conferenceId))
            ->then()
                ->integer($seatsAvailability->availableSeats()->toNative())
                    ->isEqualTo(100)
                ->and()
                ->when(
                    $this->commandBus()->dispatch(new CreateOrderCommand(
                        $orderId->toNative(),
                        $conferenceId->toNative(),
                        10
                    ))
                )
                ->and($seatsAvailability = $seatsAvailabilityRepository->get($conferenceId))
                ->and($processState = $processStateRepository->get($orderId))
                ->then()
                    ->integer($seatsAvailability->availableSeats()->toNative())
                        ->isEqualTo(90)
                    ->string($processState->getState()->toNative())
                        ->isEqualTo(OrderProcessStates::AWAITING_PAYMENT)
                    ->and()
                    ->when(
                        DomainEventPublisher::publish(
                            new OrderHasExpired($orderId)
                        )
                    )
                    ->and($seatsAvailability = $seatsAvailabilityRepository->get($conferenceId))
                    ->and($processState = $processStateRepository->get($orderId))
                    ->then()
                        ->integer($seatsAvailability->availableSeats()->toNative())
                            ->isEqualTo(100)
                        ->string($processState->getState()->toNative())
                            ->isEqualTo(OrderProcessStates::EXPIRED)
        ;
    }

    /**
     * Test rejected process.
     */
    public function testRejectedProcess()
    {
        $this
            ->given($conferenceId = ConferenceId::next())
            ->and($orderId = OrderId::next())
            ->and($seatsAvailabilityRepository = $this->writeRepository(SeatsAvailability::class))
            ->and($processStateRepository = $this->queryRepository(OrderProcessState::class))
            ->when(
                $this->commandBus()->dispatch(new CreateConferenceCommand(
                    $conferenceId->toNative(),
                    'DDD for everyone',
                    100
                ))
            )
            ->and($seatsAvailability = $seatsAvailabilityRepository->get($conferenceId))
            ->then()
                ->integer($seatsAvailability->availableSeats()->toNative())
                    ->isEqualTo(100)
                ->and()
                ->when(
                    $this->commandBus()->dispatch(new CreateOrderCommand(
                        $orderId->toNative(),
                        $conferenceId->toNative(),
                        145
                    ))
                )
                ->and($seatsAvailability = $seatsAvailabilityRepository->get($conferenceId))
                ->and($processState = $processStateRepository->get($orderId))
                ->then()
                    ->integer($seatsAvailability->availableSeats()->toNative())
                        ->isEqualTo(100)
                    ->string($processState->getState()->toNative())
                        ->isEqualTo(OrderProcessStates::REJECTED)
                    ->and()
                    ->exception(function () use ($orderId) {
                        DomainEventPublisher::publish(
                            new PaymentWasReceived($orderId)
                        );
                    })->isInstanceOf(\LogicException::class)
        ;
    }

    /**
     * Test simple process manager.
     */
    public function testSimpleProcessManager()
    {
        $this
            ->given($processManager = new SimpleProcessManager($this->commandBus()))
            ->then()
                ->exception(function () use ($processManager) {
                    $processManager->load(ConferenceId::next());
                })->isInstanceOf(\BadMethodCallException::class)
        ;

        $this
            ->given(
                $processManager = new SimpleProcessManager(
                    $this->commandBus(),
                    $this->queryRepository(OrderProcessState::class)
                )
            )
            ->then()
            ->exception(function () use ($processManager) {
                $processManager->load(ConferenceId::next());
            })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}