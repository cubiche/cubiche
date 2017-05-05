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
use Cubiche\Core\Cqrs\WriteModelInterface;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\EventSourcing\Event\PostPersistEvent;
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
     * Projector constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param PostPersistEvent $event
     */
    public function onPostPersist(PostPersistEvent $event)
    {
        // skip if the aggregate is not my write model class
        if (is_a($event->aggregate(), $this->writeModelClass())) {
            $eventStream = $event->eventStream();

            // find all read models that exist for a given write model
            $readModels = $this->readModelsFromRepository($event->aggregate()->id());
            foreach ($readModels as $readModel) {
                // create the initial projection
                $projection = new Projection($readModel, Action::UPDATE());

                // project it
                $this->projectEvents($projection, $eventStream->events());
            }

            // there is not read models for the given write model?
            if (count($readModels) == 0) {
                // create all read models of a given write model
                $readModels = $this->readModelsFromWriteModel($event->aggregate());
                foreach ($readModels as $readModel) {
                    // create the initial projection
                    $projection = new Projection($readModel, Action::NONE());

                    // project it
                    $this->projectEvents($projection, $eventStream->events());
                }
            }
        }
    }

    /**
     * @param Projection $projection
     * @param array      $events
     */
    protected function projectEvents(Projection $projection, array $events)
    {
        foreach ($events as $event) {
            $classParts = explode('\\', get_class($event));
            $method = 'project'.end($classParts);

            if (method_exists($this, $method)) {
                $this->$method($projection, $event);
            }
        }

        switch ($projection->action()) {
            case Action::CREATE():
            case Action::UPDATE():
                // the read model should be created/updated
                $this->persist($projection->readModel());
                break;
            case Action::REMOVE():
                // the read model should be removed
                $this->remove($projection->readModel());
                break;
        }
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
     * @param IdInterface $writeModelId
     *
     * @return array
     */
    abstract protected function readModelsFromRepository(IdInterface $writeModelId);

    /**
     * @param WriteModelInterface $writeModel
     *
     * @return array
     */
    abstract protected function readModelsFromWriteModel(WriteModelInterface $writeModel);

    /**
     * @return string
     */
    abstract protected function writeModelClass();

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
