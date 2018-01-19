<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing;

use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\EventSourcing\Event\PostPersistEvent;
use Cubiche\Domain\EventSourcing\Event\PostRemoveEvent;
use Cubiche\Domain\EventSourcing\Event\PrePersistEvent;
use Cubiche\Domain\EventSourcing\Event\PreRemoveEvent;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\RepositoryInterface;

/**
 * AggregateRepository class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AggregateRepository implements RepositoryInterface
{
    /**
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * @var string
     */
    protected $aggregateClassName;

    /**
     * AggregateRepository constructor.
     *
     * @param EventStoreInterface $eventStore
     * @param string              $aggregateClassName
     */
    public function __construct(EventStoreInterface $eventStore, $aggregateClassName)
    {
        $this->eventStore = $eventStore;
        $this->aggregateClassName = $aggregateClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdInterface $id)
    {
        $eventStream = $this->loadHistory($id);

        if ($eventStream !== null) {
            return call_user_func(
                array($this->aggregateClassName, 'loadFromHistory'),
                $eventStream
            );
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function persist(AggregateRootInterface $element)
    {
        $this->saveHistory($element);
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
        DomainEventPublisher::publish(new PreRemoveEvent($element));

        // remove the event stream
        $this->eventStore->remove($element->id());

        DomainEventPublisher::publish(new PostRemoveEvent($element));
    }

    /**
     * Load a aggregate history from the storage.
     *
     * @param IdInterface $id
     *
     * @return EventStream|null
     */
    protected function loadHistory(IdInterface $id)
    {
        return $this->eventStore->load($id);
    }

    /**
     * Save the aggregate history.
     *
     * @param AggregateRootInterface $aggregateRoot
     */
    protected function saveHistory(AggregateRootInterface $aggregateRoot)
    {
        $recordedEvents = $aggregateRoot->recordedEvents();
        if (count($recordedEvents) > 0) {
            // trigger pre-persist event
            DomainEventPublisher::publish(new PrePersistEvent($aggregateRoot));

            // clear events
            $aggregateRoot->clearEvents();

            // create the eventStream and persist it
            $eventStream = new EventStream(
                $aggregateRoot->id(),
                $recordedEvents
            );

            $this->eventStore->persist($eventStream);

            // publish all the recorded events
            foreach ($recordedEvents as $recordedEvent) {
                DomainEventPublisher::publish($recordedEvent);
            }

            // trigger post-persist event
            DomainEventPublisher::publish(new PostPersistEvent($aggregateRoot, $eventStream));
        }
    }
}
