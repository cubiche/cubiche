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
    public function persist(EventStream $eventStream, Version $version)
    {
        $streamName = $this->getKey($eventStream->streamName(), $version);
        if (!$this->store->containsKey($streamName)) {
            $this->store->set($streamName, new ArrayHashMap());
        }

        /** @var ArrayHashMap $streamNameCollection */
        $streamNameCollection = $this->store->get($streamName);
        if (!$streamNameCollection->containsKey($eventStream->aggregateId()->toNative())) {
            $streamNameCollection->set($eventStream->aggregateId()->toNative(), new ArrayList());
        }

        /** @var ArrayList $aggregateIdCollection */
        $aggregateIdCollection = $streamNameCollection->get($eventStream->aggregateId()->toNative());
        foreach ($eventStream->events() as $event) {
            $aggregateIdCollection->add($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($streamName, IdInterface $aggregateId, Version $version)
    {
        $key = $this->getKey($streamName, $version);
        if (!$this->store->containsKey($key)) {
            throw new \RuntimeException(sprintf(
                'The stream name %s not found in the event store.',
                $key
            ));
        }

        /** @var ArrayHashMap $streamNameCollection */
        $streamNameCollection = $this->store->get($key);
        if (!$streamNameCollection->containsKey($aggregateId->toNative())) {
            throw new \RuntimeException(sprintf(
                'Aggregate id %s of %s not found in the event store.',
                $aggregateId->toNative(),
                $key
            ));
        }

        /** @var ArrayList $aggregateIdCollection */
        $aggregateIdCollection = $streamNameCollection->get($aggregateId->toNative());

        return new EventStream(
            $streamName,
            $aggregateId,
            $aggregateIdCollection->find(Criteria::method('version')->gte($version->aggregateVersion()))->toArray()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function remove($streamName, IdInterface $aggregateId, Version $version)
    {
        $key = $this->getKey($streamName, $version);
        if (!$this->store->containsKey($key)) {
            throw new \RuntimeException(sprintf(
                'The stream name %s not found in the event store.',
                $key
            ));
        }

        /** @var ArrayHashMap $streamNameCollection */
        $streamNameCollection = $this->store->get($key);
        $streamNameCollection->removeAt($aggregateId->toNative());
    }

    /**
     * @param string  $streamName
     * @param Version $version
     *
     * @return string
     */
    protected function getKey($streamName, Version $version)
    {
        return sprintf('%s_%s', $streamName, $version->modelVersion());
    }
}
