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

use Cubiche\Domain\EventSourcing\AggregateRepository;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;

/**
 * AggregateRepositoryFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AggregateRepositoryFactory
{
    /**
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * AggregateRepositoryFactory constructor.
     *
     * @param EventStoreInterface $eventStore
     */
    public function __construct(EventStoreInterface $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param string $aggregateClassName
     *
     * @return AggregateRepository
     */
    public function create($aggregateClassName)
    {
        return new AggregateRepository($this->eventStore, $aggregateClassName);
    }
}
