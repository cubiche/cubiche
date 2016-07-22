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

use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\EventSourcing\Event\PostPersistEvent;
use Cubiche\Domain\EventSourcing\Event\PrePersistEvent;
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;
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
        $eventStream = $this->loadHistory($id);

        return call_user_func(
            array($this->aggregateClassName, 'loadFromHistory'),
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

        $this->saveHistory($element);
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
     * Load a aggregate history from the storage.
     *
     * @param IdInterface $id
     *
     * @return EventStream
     */
    protected function loadHistory(IdInterface $id)
    {
        $version = VersionManager::versionOfClass($this->aggregateClassName);

        return $this->eventStore->load($this->aggregateName, $id, $version);
    }

    /**
     * Save the aggregate history.
     *
     * @param EventSourcedAggregateRootInterface $aggregateRoot
     */
    protected function saveHistory(EventSourcedAggregateRootInterface $aggregateRoot)
    {
        $recordedEvents = $aggregateRoot->recordedEvents();
        if (count($recordedEvents) > 0) {
            DomainEventPublisher::publish(new PrePersistEvent($aggregateRoot));

            $aggregateRoot->clearEvents();
            $eventStream = new EventStream($this->aggregateName, $aggregateRoot->id(), $recordedEvents);
            $this->eventStore->persist($eventStream, $aggregateRoot->version());

            DomainEventPublisher::publish(new PostPersistEvent($aggregateRoot));
        }
    }

    /**
     * @return string
     */
    public function aggregateName()
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
