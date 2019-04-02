<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Factory;

use Cubiche\Core\Bus\Publisher\MessagePublisherInterface;
use Cubiche\Domain\EventSourcing\AggregateRepository;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\Repository\Factory\RepositoryFactoryInterface;

/**
 * AggregateRepositoryFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AggregateRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * @var MessagePublisherInterface
     */
    protected $publisher;

    /**
     * AggregateRepositoryFactory constructor.
     *
     * @param EventStoreInterface       $eventStore
     * @param MessagePublisherInterface $publisher
     */
    public function __construct(EventStoreInterface $eventStore, MessagePublisherInterface $publisher)
    {
        $this->eventStore = $eventStore;
        $this->publisher = $publisher;
    }

    /**
     * @param string $aggregateClassName
     *
     * @return AggregateRepository
     */
    public function create($aggregateClassName)
    {
        return new AggregateRepository($this->eventStore, $this->publisher, $aggregateClassName);
    }
}
