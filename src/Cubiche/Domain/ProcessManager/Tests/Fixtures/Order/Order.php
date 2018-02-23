<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\Order;

use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Event\OrderWasBooked;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Event\OrderWasCreated;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Event\OrderWasRejected;
use Cubiche\Domain\System\Integer;

/**
 * Order class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Order extends AggregateRoot
{
    /**
     * @var ConferenceId
     */
    protected $conferenceId;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $numberOfTickets;

    /**
     * @var OrderState
     */
    protected $state;

    /**
     * Order constructor.
     *
     * @param OrderId      $orderId
     * @param ConferenceId $conferenceId
     * @param int          $numberOfTickets
     */
    public function __construct(
        OrderId $orderId,
        ConferenceId $conferenceId,
        Integer $numberOfTickets
    ) {
        parent::__construct($orderId);

        $this->recordAndApplyEvent(
            new OrderWasCreated($orderId, $conferenceId, $numberOfTickets)
        );
    }

    /**
     * @return OrderId
     */
    public function orderId()
    {
        return $this->id;
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function numberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * @return OrderState
     */
    public function state()
    {
        return $this->state;
    }

    /**
     * Mark order as booked.
     */
    public function markAsBooked()
    {
        if ($this->state !== OrderState::STATE_NEW) {
            throw new \LogicException();
        }

        $this->recordAndApplyEvent(new OrderWasBooked($this->orderId()));
    }

    /**
     * Reject the order.
     */
    public function reject()
    {
        if (!in_array($this->state, [OrderState::STATE_NEW, OrderState::STATE_BOOKED], true)) {
            throw new \LogicException();
        }

        $this->recordAndApplyEvent(new OrderWasRejected($this->orderId()));
    }

    /**
     * @param OrderWasCreated $event
     */
    protected function applyOrderWasCreated(OrderWasCreated $event)
    {
        $this->conferenceId = $event->conferenceId();
        $this->numberOfTickets = $event->numberOfTickets();
        $this->state = OrderState::STATE_NEW;
    }

    /**
     * @param OrderWasBooked $event
     */
    protected function applyOrderWasBooked(OrderWasBooked $event)
    {
        $this->state = OrderState::STATE_BOOKED;
    }

    /**
     * @param OrderWasRejected $event
     */
    protected function applyOrderWasRejected(OrderWasRejected $event)
    {
        $this->state = OrderState::STATE_REJECTED;
    }
}
