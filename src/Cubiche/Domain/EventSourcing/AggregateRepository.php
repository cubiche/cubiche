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
use Cubiche\Domain\EventSourcing\Utils\NameResolver;
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
    public function persist($element)
    {
        if (!$element instanceof EventSourcedAggregateRootInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The object must be an instance of %s. Instance of %s given',
                EventSourcedAggregateRootInterface::class,
                is_object($element) ? get_class($element) : gettype($element)
            ));
        }

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
    public function remove($element)
    {
        if (!$element instanceof EventSourcedAggregateRootInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The object must be an instance of %s. Instance of %s given',
                EventSourcedAggregateRootInterface::class,
                is_object($element) ? get_class($element) : gettype($element)
            ));
        }

        DomainEventPublisher::publish(new PreRemoveEvent($element));

        // remove the event stream
        $this->eventStore->remove($this->streamName($element->id()));

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
        return $this->eventStore->load($this->streamName($id));
    }

    /**
     * Save the aggregate history.
     *
     * @param EventSourcedAggregateRootInterface $aggregateRoot
     */
    protected function saveHistory(EventSourcedAggregateRootInterface $aggregateRoot)
    {
        $recordedEvents = $aggregateRoot->recordedEvents();
        if (count($recordedEvents) > 0) {
            DomainEventPublisher::publish(new PrePersistEvent($aggregateRoot));

            // clear events
            $aggregateRoot->clearEvents();

            // create the eventStream and persist it
            $eventStream = new EventStream(
                $this->streamName($aggregateRoot->id()),
                $aggregateRoot->id(),
                $recordedEvents
            );

            $this->eventStore->persist($eventStream);

            DomainEventPublisher::publish(new PostPersistEvent($aggregateRoot, $eventStream));
        }
    }

    /**
     * @param IdInterface $id
     *
     * @return string
     */
    protected function streamName(IdInterface $id)
    {
        return NameResolver::resolve($this->aggregateClassName, $id);
    }
}
