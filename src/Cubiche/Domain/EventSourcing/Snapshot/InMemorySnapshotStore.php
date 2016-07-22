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
    public function persist(Snapshot $snapshot)
    {
        $aggregateType = $this->getKey($snapshot->aggregateType(), $snapshot->version());
        if (!$this->store->containsKey($aggregateType)) {
            $this->store->set($aggregateType, new ArrayHashMap());
        }

        /** @var ArrayHashMap $aggregateTypeCollection */
        $aggregateTypeCollection = $this->store->get($aggregateType);
        $aggregateTypeCollection->set($snapshot->aggregateId()->toNative(), $snapshot);
    }

    /**
     * {@inheritdoc}
     */
    public function load($aggregateType, IdInterface $aggregateId, Version $version)
    {
        $aggregateType = $this->getKey($aggregateType, $version);
        if (!$this->store->containsKey($aggregateType)) {
            return;
        }

        /** @var ArrayHashMap $aggregateTypeCollection */
        $aggregateTypeCollection = $this->store->get($aggregateType);
        if (!$aggregateTypeCollection->containsKey($aggregateId->toNative())) {
            return;
        }

        return $aggregateTypeCollection->get($aggregateId->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function remove($aggregateType, IdInterface $aggregateId, Version $version)
    {
        $aggregateType = $this->getKey($aggregateType, $version);
        if (!$this->store->containsKey($aggregateType)) {
            return;
        }

        /** @var ArrayHashMap $aggregateTypeCollection */
        $aggregateTypeCollection = $this->store->get($aggregateType);
        $aggregateTypeCollection->removeAt($aggregateId->toNative());
    }

    /**
     * @param string  $aggregateType
     * @param Version $version
     *
     * @return string
     */
    protected function getKey($aggregateType, Version $version)
    {
        return sprintf('%s_%s', $aggregateType, $version->modelVersion());
    }
}
