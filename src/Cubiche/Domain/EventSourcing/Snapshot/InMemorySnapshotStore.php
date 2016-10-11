<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Snapshot;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\Model\IdInterface;

/**
 * InMemorySnapshotStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemorySnapshotStore implements SnapshotStoreInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $store;

    /**
     * InMemorySnapshot constructor.
     */
    public function __construct()
    {
        $this->store = new ArrayHashMap();
    }

    /**
     * {@inheritdoc}
     */
    public function persist(Snapshot $snapshot, Version $applicationVersion)
    {
        $applicationKey = $this->getApplicationKey($applicationVersion);
        if (!$this->store->containsKey($applicationKey)) {
            $this->store->set($applicationKey, new ArrayHashMap());
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $aggregateKey = $this->getAggregateKey($snapshot->aggregateType(), $snapshot->version());

        if (!$applicationCollection->containsKey($aggregateKey)) {
            $applicationCollection->set($aggregateKey, new ArrayHashMap());
        }

        /** @var ArrayHashMap $aggregateCollection */
        $aggregateCollection = $applicationCollection->get($aggregateKey);
        $aggregateCollection->set($snapshot->aggregateId()->toNative(), $snapshot);
    }

    /**
     * {@inheritdoc}
     */
    public function load(
        $aggregateType,
        IdInterface $aggregateId,
        Version $aggregateVersion,
        Version $applicationVersion
    ) {
        $applicationKey = $this->getApplicationKey($applicationVersion);
        if (!$this->store->containsKey($applicationKey)) {
            return;
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $aggregateKey = $this->getAggregateKey($aggregateType, $aggregateVersion);

        if (!$applicationCollection->containsKey($aggregateKey)) {
            return;
        }

        /** @var ArrayHashMap $aggregateCollection */
        $aggregateCollection = $applicationCollection->get($aggregateKey);

        return $aggregateCollection->get($aggregateId->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function remove(
        $aggregateType,
        IdInterface $aggregateId,
        Version $aggregateVersion,
        Version $applicationVersion
    ) {
        $applicationKey = $this->getApplicationKey($applicationVersion);
        if (!$this->store->containsKey($applicationKey)) {
            return;
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $aggregateKey = $this->getAggregateKey($aggregateType, $aggregateVersion);

        if (!$applicationCollection->containsKey($aggregateKey)) {
            return;
        }

        /** @var ArrayHashMap $aggregateCollection */
        $aggregateCollection = $applicationCollection->get($aggregateKey);
        $aggregateCollection->removeAt($aggregateId->toNative());
    }

    /**
     * @param Version $applicationVersion
     *
     * @return string
     */
    protected function getApplicationKey(Version $applicationVersion)
    {
        return str_replace('.', '_', $applicationVersion->__toString());
    }

    /**
     * @param string  $aggregateType
     * @param Version $aggregateVersion
     *
     * @return string
     */
    protected function getAggregateKey($aggregateType, Version $aggregateVersion)
    {
        return sprintf('%s_%s_%s', $aggregateType, $aggregateVersion->major(), $aggregateVersion->minor());
    }
}
