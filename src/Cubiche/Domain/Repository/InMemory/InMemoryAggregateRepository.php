<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Repository\InMemory;

use Cubiche\Core\Bus\Message\Publisher\MessagePublisherInterface;
use Cubiche\Domain\EventSourcing\AggregateRootInterface;
use Cubiche\Domain\EventSourcing\Event\PostPersistEvent;
use Cubiche\Domain\EventSourcing\Event\PostRemoveEvent;
use Cubiche\Domain\EventSourcing\Event\PrePersistEvent;
use Cubiche\Domain\EventSourcing\Event\PreRemoveEvent;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\RepositoryInterface;

/**
 * InMemoryAggregateRepository Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class InMemoryAggregateRepository implements RepositoryInterface
{
    /**
     * @var InMemoryRepository
     */
    protected $repository;

    /**
     * @var MessagePublisherInterface
     */
    protected $publisher;

    /**
     * InMemoryAggregateRepository constructor.
     *
     * @param MessagePublisherInterface $publisher
     */
    public function __construct(MessagePublisherInterface $publisher)
    {
        $this->repository = new InMemoryRepository();
        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdInterface $id)
    {
        return $this->repository->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function persist(AggregateRootInterface $element)
    {
        $recordedEvents = $element->recordedMessages();
        if (count($recordedEvents) > 0) {
            // trigger pre-persist event
            $this->publisher->publishMessage(new PrePersistEvent($element));

            // clear events
            $element->clearMessages();

            $this->repository->persist($element);

            // publish all the recorded events
            foreach ($recordedEvents as $recordedEvent) {
                $this->publisher->recordMessage($recordedEvent);
            }

            // trigger post-persist event
            $this->publisher->publishMessage(new PostPersistEvent($element, null));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function persistAll($elements)
    {
        foreach ($elements as $element) {
            $this->persist($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(AggregateRootInterface $element)
    {
        $this->publisher->publishMessage(new PreRemoveEvent($element));

        // remove the aggregate
        $this->repository->remove($element);

        $this->publisher->publishMessage(new PostRemoveEvent($element));
    }
}
