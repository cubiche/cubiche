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
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;
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
    public function persist(Snapshot $snapshot)
    {
        $applicationKey = $this->getApplicationKey();
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
    public function load($aggregateType, IdInterface $aggregateId, Version $version)
    {
        $applicationKey = $this->getApplicationKey();
        if (!$this->store->containsKey($applicationKey)) {
            return;
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $aggregateKey = $this->getAggregateKey($aggregateType, $version);

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
    public function remove($aggregateType, IdInterface $aggregateId, Version $version)
    {
        $applicationKey = $this->getApplicationKey();
        if (!$this->store->containsKey($applicationKey)) {
            return;
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);
        $aggregateKey = $this->getAggregateKey($aggregateType, $version);

        if (!$applicationCollection->containsKey($aggregateKey)) {
            return;
        }

        /** @var ArrayHashMap $aggregateCollection */
        $aggregateCollection = $applicationCollection->get($aggregateKey);
        $aggregateCollection->removeAt($aggregateId->toNative());
    }

    /**
     * @return string
     */
    protected function getApplicationKey()
    {
        return str_replace('.', '_', VersionManager::currentApplicationVersion()->__toString());
    }

    /**
     * @param string  $aggregateType
     * @param Version $version
     *
     * @return string
     */
    protected function getAggregateKey($aggregateType, Version $version)
    {
        return sprintf('%s_%s', $aggregateType, $version->major(), $version->minor());
    }
}
