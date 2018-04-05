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

use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\CreateOrderCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\ExpireOrderCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\MarkOrderAsBookedCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\RejectOrderCommand;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\Integer;

/**
 * OrderCommandHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class OrderCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * OrderCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateOrderCommand $command
     */
    public function createOrder(CreateOrderCommand $command)
    {
        $order = new Order(
            OrderId::fromNative($command->orderId()),
            ConferenceId::fromNative($command->conferenceId()),
            Integer::fromNative($command->numberOfTickets())
        );

        $this->repository->persist($order);
    }

    /**
     * @param MarkOrderAsBookedCommand $command
     */
    public function markOrderAsBooked(MarkOrderAsBookedCommand $command)
    {
        $order = $this->findOr404($command->orderId());
        $order->markAsBooked();

        $this->repository->persist($order);
    }

    /**
     * @param RejectOrderCommand $command
     */
    public function rejectOrder(RejectOrderCommand $command)
    {
        $order = $this->findOr404($command->orderId());
        $order->reject();

        $this->repository->persist($order);
    }

    /**
     * @param string $orderId
     *
     * @return Order
     */
    private function findOr404($orderId)
    {
        $order = $this->repository->get(OrderId::fromNative($orderId));
        if ($order === null) {
            throw new \RuntimeException(sprintf(
                'There is no order with id: %s',
                $orderId
            ));
        }

        return $order;
    }
}
