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
use Cubiche\Domain\EventSourcing\Versioning\Version;

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
    public function persist(EventStream $eventStream)
    {
        $version = 0;
        if (!$this->store->containsKey($eventStream->streamName())) {
            $this->store->set($eventStream->streamName(), new ArrayList());
        }

        /** @var ArrayList $streamCollection */
        $streamCollection = $this->store->get($eventStream->streamName());

        /** @var DomainEventInterface $event */
        foreach ($eventStream->events() as $event) {
            $streamCollection->add($event);

            $version = $event->version();
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($streamName)
    {
        $this->store->removeAt($streamName);
    }

    /**
     * {@inheritdoc}
     */
    public function load($streamName, $version = 0)
    {
        /** @var ArrayList $streamCollection */
        $streamCollection = $this->store->get($streamName);

        if ($streamCollection !== null) {
            $events = $streamCollection->find(Criteria::method('version')->gte($version))->toArray();
            if (count($events) > 0) {
                /** @var DomainEventInterface $event */
                $event = $events[0];

                return new EventStream($streamName, $event->aggregateId(), $events);
            }
        }

        return;
    }
}
