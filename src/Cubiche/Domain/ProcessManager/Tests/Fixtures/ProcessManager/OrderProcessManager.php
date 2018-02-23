<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager;

use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\ProcessManager\ProcessManager;
use Cubiche\Domain\ProcessManager\ProcessManagerConfig;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Event\ConferenceWasCreated;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Event\OrderHasExpired;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Event\PaymentWasReceived;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\MarkOrderAsBookedCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\RejectOrderCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Event\OrderWasCreated;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\OrderId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CancelSeatReservationCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CommitSeatReservationCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CreateSeatsAvailabilityCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\MakeSeatReservationCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Event\ReservationWasAccepted;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Event\ReservationWasRejected;

/**
 * OrderProcessManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class OrderProcessManager extends ProcessManager implements DomainEventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    protected function build(ProcessManagerConfig $config)
    {
        $config
            ->addStates(
                array(
                    OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION,
                    OrderProcessStates::AWAITING_PAYMENT,
                    OrderProcessStates::REJECTED,
                    OrderProcessStates::COMPLETED,
                    OrderProcessStates::EXPIRED,
                )
            )
            ->addTransitions(
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
    }

    /**
     * @param ConferenceWasCreated $event
     */
    public function whenConferenceWasCreated(ConferenceWasCreated $event)
    {
        $this->dispatch(new CreateSeatsAvailabilityCommand(
            $event->conferenceId()->toNative(),
            $event->availableTickets()->toNative()
        ));
    }

    /**
     * @param OrderWasCreated $event
     */
    public function whenOrderWasCreated(OrderWasCreated $event)
    {
        /*
         * Initial state: AWAITING_RESERVATION_CONFIRMATION
         */
        $state = new OrderProcessState($event->orderId(), $event->conferenceId());
        $this->persist($state);

        $this->dispatch(
            new MakeSeatReservationCommand(
                $event->conferenceId()->toNative(),
                $event->orderId()->toNative(),
                $event->numberOfTickets()->toNative()
            )
        );
    }

    /**
     * @param ReservationWasAccepted $event
     */
    public function whenReservationWasAccepted(ReservationWasAccepted $event)
    {
        /** @var OrderProcessState $state */
        $state = $this->load(OrderId::fromNative($event->reservationId()->toNative()));

        /*
         * Transition to state: AWAITING_PAYMENT
         */
        $this->apply('accept', $state);
        $this->persist($state);

        $this->dispatch(
            new MarkOrderAsBookedCommand(
                $event->reservationId()->toNative()
            )
        );
    }

    /**
     * @param ReservationWasRejected $event
     */
    public function whenReservationWasRejected(ReservationWasRejected $event)
    {
        /** @var OrderProcessState $state */
        $state = $this->load(OrderId::fromNative($event->reservationId()->toNative()));

        /*
         * Transition to state: REJECTED
         */
        $this->apply('reject', $state);
        $this->persist($state);

        $this->dispatch(
            new RejectOrderCommand(
                $event->reservationId()->toNative()
            )
        );
    }

    /**
     * @param PaymentWasReceived $event
     */
    public function whenPaymentWasReceived(PaymentWasReceived $event)
    {
        /** @var OrderProcessState $state */
        $state = $this->load($event->orderId());

        /*
         * Transition to state: COMPLETED
         */
        $this->apply('complete', $state);
        $this->persist($state);

        $this->dispatch(
            new CommitSeatReservationCommand(
                $state->conferenceId()->toNative(),
                $event->orderId()->toNative()
            )
        );
    }

    /**
     * @param OrderHasExpired $event
     */
    public function whenOrderHasExpired(OrderHasExpired $event)
    {
        /** @var OrderProcessState $state */
        $state = $this->load($event->orderId());

        /*
         * Transition to state: EXPIRED
         */
        $this->apply('expire', $state);
        $this->persist($state);

        $this->dispatch(
            new CancelSeatReservationCommand(
                $state->conferenceId()->toNative(),
                $event->orderId()->toNative()
            )
        );

        $this->dispatch(
            new RejectOrderCommand(
                $event->orderId()->toNative()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function name()
    {
        return 'app.process_manager.seat_reservation';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ConferenceWasCreated::class => array('whenConferenceWasCreated', 250),
            OrderWasCreated::class => array('whenOrderWasCreated', 250),
            ReservationWasAccepted::class => array('whenReservationWasAccepted', 250),
            ReservationWasRejected::class => array('whenReservationWasRejected', 250),
            PaymentWasReceived::class => array('whenPaymentWasReceived', 250),
            OrderHasExpired::class => array('whenOrderHasExpired', 250),
        );
    }
}
