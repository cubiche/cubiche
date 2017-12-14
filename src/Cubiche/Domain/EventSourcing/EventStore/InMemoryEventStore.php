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
use Cubiche\Domain\Model\IdInterface;

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
        if (!$this->store->containsKey($eventStream->id()->toNative())) {
            $this->store->set($eventStream->id()->toNative(), new ArrayList());
        }

        /** @var ArrayList $streamCollection */
        $streamCollection = $this->store->get($eventStream->id()->toNative());

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
    public function remove(IdInterface $id)
    {
        $this->store->removeAt($id->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function load(IdInterface $id, $version = 0)
    {
        /** @var ArrayList $streamCollection */
        $streamCollection = $this->store->get($id->toNative());

        if ($streamCollection !== null) {
            $events = $streamCollection->find(Criteria::method('version')->gte($version))->toArray();
            if (count($events) > 0) {
                return new EventStream($id, $events);
            }
        }

        return;
    }
}
