<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\EventSourcing;

use Cubiche\Core\Serializer\SerializerInterface;
use Cubiche\Core\Storage\StorageInterface;
use Cubiche\Domain\Model\AggregateRootInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * SnapshotStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SnapshotStore
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * SnapshotStore constructor.
     *
     * @param StorageInterface    $storage
     * @param SerializerInterface $serializer
     */
    public function __construct(StorageInterface $storage, SerializerInterface $serializer)
    {
        $this->storage = $storage;
        $this->serializer = $serializer;
    }

    /**
     * @param Snapshot $snapshot
     */
    public function persist(Snapshot $snapshot)
    {
        $key = $this->createKey(
            $snapshot->className(),
            $snapshot->aggregate()->id()
        );

        $this->storage->set($key, $this->serializeSnapshot($snapshot));
    }

    /**
     * @param string      $className
     * @param IdInterface $aggregateId
     *
     * @return Snapshot
     */
    public function getSnapshotFor($className, IdInterface $aggregateId)
    {
        $key = $this->createKey($className, $aggregateId);

        return $this->deserializeSnapshot(
            $this->storage->get($key)
        );
    }

    /**
     * @param string      $className
     * @param IdInterface $aggregateId
     *
     * @return string
     */
    protected function createKey($className, IdInterface $aggregateId)
    {
        $classParts = explode('\\', $className);

        return sprintf(
            'snapshots:%s:%s',
            strtolower(end($classParts)),
            $aggregateId->toNative()
        );
    }

    /**
     * @param Snapshot $snapshot
     *
     * @return string
     */
    protected function serializeSnapshot(Snapshot $snapshot)
    {
        $aggregate = $snapshot->aggregate();
        $aggregateData = $this->serializer->serialize($aggregate, 'json');

        return $this->serializer->serialize(
            array(
                'snapshotVersion' => $snapshot->version(),
                'aggregateType' => $snapshot->className(),
                'aggregateData' => $aggregateData,
            ),
            'json'
        );
    }

    /**
     * @param null $data
     *
     * @return Snapshot|null
     */
    protected function deserializeSnapshot($data = null)
    {
        if ($data === null) {
            return;
        }

        $serializedSnapshot = $this->serializer->deserialize($data, 'array', 'json');

        /** @var AggregateRootInterface $aggregate */
        $aggregate = $this->serializer->deserialize(
            $serializedSnapshot['aggregateData'],
            $serializedSnapshot['aggregateType'],
            'json'
        );

        return new Snapshot(
            $serializedSnapshot['snapshotVersion'],
            $aggregate
        );
    }
}
