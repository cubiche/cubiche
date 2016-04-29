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

use DateTimeImmutable;
use Cubiche\Domain\Serializer\SerializerInterface;
use Cubiche\Domain\Storage\StorageInterface;

/**
 * EventStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventStore
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
     * EventStore constructor.
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
     * @param EventStream $eventStream
     */
    public function persist(EventStream $eventStream)
    {
        $key = sprintf('events:%s', $eventStream->aggregateId()->toNative());
        foreach ($eventStream->events() as $event) {
            $date = new DateTimeImmutable();

            $data = $this->serializer->serialize(
                $event,
                'json'
            );

            $this->storage->set(
                $key,
                $this->serializer->serialize([
                    'className' => get_class($event),
                    'createdAt' => $date->format('YmdHis'),
                    'data' => $data,
                ], 'json')
            );
        }
    }

    /**
     * @param IdInterface $aggregateId
     */
    public function loadEvents(IdInterface $aggregateId)
    {
        //$key = sprintf('events:%s', $aggregateId->toNative());
    }
}
