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

use Cubiche\Core\Bus\Publisher\MessagePublisherInterface;
use Cubiche\Domain\EventSourcing\Event\PostPersistEvent;
use Cubiche\Domain\EventSourcing\Event\PostRemoveEvent;
use Cubiche\Domain\EventSourcing\Event\PrePersistEvent;
use Cubiche\Domain\EventSourcing\Event\PreRemoveEvent;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\EventStore\StreamName;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\StringLiteral;

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
     * @var MessagePublisherInterface
     */
    protected $publisher;

    /**
     * @var string
     */
    protected $aggregateClassName;

    /**
     * AggregateRepository constructor.
     *
     * @param EventStoreInterface       $eventStore
     * @param MessagePublisherInterface $publisher
     * @param string                    $aggregateClassName
     */
    public function __construct(
        EventStoreInterface $eventStore,
        MessagePublisherInterface $publisher,
        $aggregateClassName
    ) {
        $this->eventStore = $eventStore;
        $this->publisher = $publisher;
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
        $this->publisher->publishMessage(new PreRemoveEvent($element));

        // remove the event stream
        $this->eventStore->remove($this->streamName($element->id()));

        $this->publisher->publishMessage(new PostRemoveEvent($element));
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
     * @param AggregateRootInterface $aggregateRoot
     */
    protected function saveHistory(AggregateRootInterface $aggregateRoot)
    {
        $recordedEvents = $aggregateRoot->recordedMessages();
        if (count($recordedEvents) > 0) {
            // trigger pre-persist event
            $this->publisher->publishMessage(new PrePersistEvent($aggregateRoot));

            // clear events
            $aggregateRoot->clearMessages();

            // create the eventStream and persist it
            $eventStream = new EventStream(
                $this->streamName($aggregateRoot->id()),
                $recordedEvents
            );

            $this->eventStore->persist($eventStream);

            // record all the recorded events to publish it's later
            foreach ($recordedEvents as $recordedEvent) {
                $this->publisher->recordMessage($recordedEvent);
            }

            // trigger post-persist event
            $this->publisher->publishMessage(new PostPersistEvent($aggregateRoot, $eventStream));
        }
    }

    /**
     * @param IdInterface $id
     *
     * @return StreamName
     */
    protected function streamName(IdInterface $id)
    {
        return new StreamName($id, $this->shortClassName($this->aggregateClassName));
    }

    /**
     * @param mixed $aggregateRoot
     *
     * @return StringLiteral
     */
    protected function shortClassName($aggregateRoot)
    {
        $shortClass = (new \ReflectionClass($aggregateRoot))->getShortName();

        return StringLiteral::fromNative($shortClass);
    }
}
