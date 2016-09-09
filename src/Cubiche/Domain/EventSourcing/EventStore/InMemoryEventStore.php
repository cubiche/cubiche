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
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;
use Cubiche\Domain\Identity\StringId;
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
        $applicationKey = $this->getApplicationKey();
        if (!$this->store->containsKey($applicationKey)) {
            $this->store->set($applicationKey, new ArrayHashMap());
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $streamKey = $this->getStreamKey($eventStream->streamName(), $version);

        if (!$applicationCollection->containsKey($streamKey)) {
            $applicationCollection->set($streamKey, new ArrayHashMap());
        }

        /** @var ArrayHashMap $streamCollection */
        $streamCollection = $applicationCollection->get($streamKey);
        $aggregateKey = $eventStream->aggregateId()->toNative();

        if (!$streamCollection->containsKey($aggregateKey)) {
            $streamCollection->set($aggregateKey, new ArrayList());
        }

        /** @var ArrayList $aggregateIdCollection */
        $aggregateIdCollection = $streamCollection->get($aggregateKey);
        foreach ($eventStream->events() as $event) {
            $aggregateIdCollection->add($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($streamName, IdInterface $aggregateId, Version $version)
    {
        $applicationKey = $this->getApplicationKey();
        if (!$this->store->containsKey($applicationKey)) {
            throw new \RuntimeException(sprintf(
                'The application %s not found in the event store.',
                $applicationKey
            ));
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $streamKey = $this->getStreamKey($streamName, $version);

        if (!$applicationCollection->containsKey($streamKey)) {
            throw new \RuntimeException(sprintf(
                'The stream name %s of application %s not found in the event store.',
                $streamKey,
                $applicationKey
            ));
        }

        /** @var ArrayHashMap $streamCollection */
        $streamCollection = $applicationCollection->get($streamKey);
        $aggregateKey = $aggregateId->toNative();

        if (!$streamCollection->containsKey($aggregateKey)) {
            throw new \RuntimeException(sprintf(
                'Aggregate id %s of the stream %s in the application %s not found in the event store.',
                $aggregateKey,
                $streamKey,
                $applicationKey
            ));
        }

        /** @var ArrayList $aggregateIdCollection */
        $aggregateIdCollection = $streamCollection->get($aggregateKey);

        return new EventStream(
            $streamName,
            $aggregateId,
            $aggregateIdCollection->find(Criteria::method('version')->gte($version->patch()))->toArray()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function remove($streamName, IdInterface $aggregateId, Version $version)
    {
        $applicationKey = $this->getApplicationKey();
        if (!$this->store->containsKey($applicationKey)) {
            throw new \RuntimeException(sprintf(
                'The application %s not found in the event store.',
                $applicationKey
            ));
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $streamKey = $this->getStreamKey($streamName, $version);

        if (!$applicationCollection->containsKey($streamKey)) {
            throw new \RuntimeException(sprintf(
                'The stream name %s of application %s not found in the event store.',
                $streamKey,
                $applicationKey
            ));
        }

        /** @var ArrayHashMap $streamCollection */
        $streamCollection = $applicationCollection->get($streamKey);
        $streamCollection->removeAt($aggregateId->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function loadAll($streamName, Version $version)
    {
        $streams = array();

        $applicationKey = $this->getApplicationKey();
        if (!$this->store->containsKey($applicationKey)) {
            throw new \RuntimeException(sprintf(
                'The application %s not found in the event store.',
                $applicationKey
            ));
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $streamKey = $this->getStreamKey($streamName, $version);

        if (!$applicationCollection->containsKey($streamKey)) {
            throw new \RuntimeException(sprintf(
                'The stream name %s of application %s not found in the event store.',
                $streamKey,
                $applicationKey
            ));
        }

        /** @var ArrayHashMap $streamCollection */
        $streamCollection = $applicationCollection->get($streamKey);

        /** @var ArrayList $aggregateIdCollection */
        foreach ($streamCollection as $aggregateKey => $aggregateIdCollection) {
            $streams[] = new EventStream(
                $streamName,
                StringId::fromNative($aggregateKey),
                $aggregateIdCollection->toArray()
            );
        }

        return $streams;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAll($streamName, Version $version)
    {
        $applicationKey = $this->getApplicationKey();
        if (!$this->store->containsKey($applicationKey)) {
            throw new \RuntimeException(sprintf(
                'The application %s not found in the event store.',
                $applicationKey
            ));
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $streamKey = $this->getStreamKey($streamName, $version);

        $applicationCollection->removeAt($streamKey);
    }

    /**
     * @return string
     */
    protected function getApplicationKey()
    {
        return str_replace('.', '_', VersionManager::currentApplicationVersion()->__toString());
    }

    /**
     * @param string  $streamName
     * @param Version $version
     *
     * @return string
     */
    protected function getStreamKey($streamName, Version $version)
    {
        return sprintf('%s_%s_%s', $streamName, $version->major(), $version->minor());
    }
}
