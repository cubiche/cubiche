<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\EventStore;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\EventSourcing\DomainEventInterface;

/**
 * InMemoryEventStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryEventStore implements EventStoreInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $store;

    /**
     * InMemoryEventStore constructor.
     */
    public function __construct()
    {
        $this->store = new ArrayHashMap();
    }

    /**
     * {@inheritdoc}
     */
    public function persist(EventStreamInterface $eventStream)
    {
        $version = 0;
        if (!$this->store->containsKey($eventStream->streamName()->__toString())) {
            $this->store->set($eventStream->streamName()->__toString(), new ArrayList());
        }

        /** @var ArrayList $streamCollection */
        $streamCollection = $this->store->get($eventStream->streamName()->__toString());

        /** @var DomainEventInterface $event */
        foreach ($eventStream as $event) {
            $streamCollection->add($event);

            $version = $event->version();
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(StreamName $streamName)
    {
        $this->store->removeAt($streamName->__toString());
    }

    /**
     * {@inheritdoc}
     */
    public function load(StreamName $streamName, $version = 0)
    {
        /** @var ArrayList $streamCollection */
        $streamCollection = $this->store->get($streamName->__toString());

        if ($streamCollection !== null) {
            $events = $streamCollection->find(Criteria::method('version')->gte($version))->toArray();
            if (count($events) > 0) {
                return new EventStream($streamName, $events);
            }
        }

        return;
    }
}
