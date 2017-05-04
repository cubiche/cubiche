<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Projector;

use Cubiche\Core\Cqrs\ReadModelInterface;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\EventSourcing\Event\PostPersistEvent;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * Projector class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class Projector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * Projector constructor.
     *
     * @param QueryRepositoryInterface $repository
     * @param EventStoreInterface      $eventStore
     */
    public function __construct(QueryRepositoryInterface $repository, EventStoreInterface $eventStore)
    {
        $this->repository = $repository;
        $this->eventStore = $eventStore;
    }

    /**
     * @param PostPersistEvent $event
     */
    public function onPostPersist(PostPersistEvent $event)
    {
        // skip if the aggregate is not my write model class
        if (is_a($event->aggregate(), $this->writeModelClass())) {
            /** @var ReadModelInterface $readModel */
            $readModel = $this->repository->get($event->aggregate()->id());
            $eventStream = $event->eventStream();

            // there is a projected read model?
            if ($readModel !== null) {
                // something change and has to be removed?
                if ($this->shouldBeRemoved($eventStream)) {
                    // remove it
                    $this->remove($readModel);

                    return;
                }
            } else {
                // the write model should be projected?
                if (!$this->shouldBeProjected($eventStream)) {
                    return;
                }

                // we have a write model that has never been projected
                // so, we need the complete stream history
                $eventStream = $this->loadHistory(
                    $event->aggregate()->id(),
                    $eventStream->streamName(),
                    $event->aggregateClassName()
                );
            }

            $this->projectAndPersistEvents($eventStream, $readModel);
        }
    }

    /**
     * @param EventStream        $eventStream
     * @param ReadModelInterface $readModel
     */
    protected function projectAndPersistEvents(EventStream $eventStream, ReadModelInterface $readModel = null)
    {
        // get the read model with the new changes
        $readModel = $this->projectEventStream($eventStream, $readModel);

        // persist the read model
        $this->persist($readModel);
    }

    /**
     * Projects all the events into the read model.
     *
     * @param EventStream        $eventStream
     * @param ReadModelInterface $readModel
     *
     * @return ReadModelInterface|null
     */
    protected function projectEventStream(EventStream $eventStream, ReadModelInterface $readModel = null)
    {
        foreach ($eventStream->events() as $event) {
            $classParts = explode('\\', get_class($event));
            $method = 'project'.end($classParts);

            if (method_exists($this, $method)) {
                $result = $this->$method($event, $readModel);
                if ($result !== null) {
                    $readModel = $result;
                }
            }
        }

        return $readModel;
    }

    /**
     * @param ReadModelInterface $readModel
     */
    protected function persist(ReadModelInterface $readModel)
    {
        $this->repository->persist($readModel);
    }

    /**
     * @param ReadModelInterface $readModel
     */
    protected function remove(ReadModelInterface $readModel)
    {
        $this->repository->remove($readModel);
    }

    /**
     * Load a aggregate history from the storage.
     *
     * @param IdInterface $id
     * @param string      $streamName
     * @param string      $aggregateClassName
     *
     * @return EventStream
     */
    protected function loadHistory(IdInterface $id, $streamName, $aggregateClassName)
    {
        $applicationVersion = VersionManager::currentApplicationVersion();
        $aggregateVersion = VersionManager::versionOfClass($aggregateClassName, $applicationVersion);

        return $this->eventStore->load($streamName, $id, $aggregateVersion, $applicationVersion);
    }

    /**
     * @return string
     */
    abstract protected function writeModelClass();

    /**
     * @param EventStream $eventStream
     *
     * @return bool
     */
    abstract protected function shouldBeProjected(EventStream $eventStream);

    /**
     * @param EventStream $eventStream
     *
     * @return bool
     */
    abstract protected function shouldBeRemoved(EventStream $eventStream);

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            PostPersistEvent::class => array('onPostPersist', 250),
        );
    }
}
