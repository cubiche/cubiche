<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing;

use Cubiche\Domain\EventSourcing\Aggregate\Versioning\VersionManager;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\RepositoryInterface;

/**
 * EventSourcedAggregateRepository class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventSourcedAggregateRepository implements RepositoryInterface
{
    /**
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * @var string
     */
    protected $aggregateClassName;

    /**
     * @var string
     */
    protected $aggregateName;

    /**
     * AggregateRepository constructor.
     *
     * @param EventStoreInterface $eventStore
     * @param string              $aggregateClassName
     */
    public function __construct(EventStoreInterface $eventStore, $aggregateClassName)
    {
        $this->eventStore = $eventStore;

        $this->aggregateClassName = $aggregateClassName;
        $this->aggregateName = $this->aggregateName();
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdInterface $id)
    {
        $version = VersionManager::versionOfClass($this->aggregateClassName);
        $eventStream = $this->eventStore->load($this->aggregateName, $id, $version);

        return call_user_func(
            $this->aggregateClassName,
            'loadFromHistory',
            $eventStream
        );
    }

    /**
     * {@inheritdoc}
     */
    public function persist($element)
    {
        if (!$element instanceof EventSourcedAggregateRootInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The object must be an instance of %s. Instance of %s given',
                EventSourcedAggregateRootInterface::class,
                is_object($element) ? get_class($element) : gettype($element)
            ));
        }

        $recordedEvents = $element->recordedEvents();
        if (count($recordedEvents) > 0) {
            $element->clearEvents();

            $eventStream = new EventStream($this->aggregateName, $element->id(), $recordedEvents);
            $this->eventStore->persist($eventStream, $element->version());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function persistAll($elements)
    {
        foreach ($elements as $element) {
            $this->persist($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($element)
    {
        if (!$element instanceof EventSourcedAggregateRootInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The object must be an instance of %s. Instance of %s given',
                EventSourcedAggregateRootInterface::class,
                is_object($element) ? get_class($element) : gettype($element)
            ));
        }

        $this->eventStore->remove($this->aggregateName, $element->id(), $element->version());
    }

    /**
     * @return string
     */
    protected function aggregateName()
    {
        $pieces = explode(' ', trim(preg_replace('([A-Z])', ' $0', $this->shortClassName())));

        return strtolower(implode('_', $pieces));
    }

    /**
     * @return string
     */
    protected function shortClassName()
    {
        // If class name has a namespace separator, only take last portion
        if (strpos($this->aggregateClassName, '\\') !== false) {
            return substr($this->aggregateClassName, strrpos($this->aggregateClassName, '\\') + 1);
        }

        return $this->aggregateClassName;
    }
}
