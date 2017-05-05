<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Fixtures\Projector;

use Cubiche\Core\Cqrs\WriteModelInterface;
use Cubiche\Domain\EventSourcing\Projector\Action;
use Cubiche\Domain\EventSourcing\Projector\Projection;
use Cubiche\Domain\EventSourcing\Projector\Projector;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasPublished;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasUnPublished;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\PostEventSourced;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\ReadModel\PublishedPost;
use Cubiche\Domain\Model\IdInterface;

/**
 * PublishedPostProjector class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PublishedPostProjector extends Projector
{
    /**
     * @param Projection          $projection
     * @param PostTitleWasChanged $event
     */
    public function projectPostTitleWasChanged(Projection $projection, PostTitleWasChanged $event)
    {
        /** @var PublishedPost $readModel */
        $readModel = $projection->readModel();

        $readModel->setTitle($event->title());
    }

    /**
     * @param Projection       $projection
     * @param PostWasPublished $event
     */
    public function projectPostWasPublished(Projection $projection, PostWasPublished $event)
    {
        $projection->setAction(Action::CREATE());
    }

    /**
     * @param Projection         $projection
     * @param PostWasUnPublished $event
     */
    public function projectPostWasUnPublished(Projection $projection, PostWasUnPublished $event)
    {
        $projection->setAction(Action::REMOVE());
    }

    /**
     * {@inheritdoc}
     */
    protected function readModelsFromRepository(IdInterface $writeModelId)
    {
        $readModel = $this->repository->get($writeModelId);
        if ($readModel !== null) {
            return array($readModel);
        }

        return array();
    }

    /**
     * {@inheritdoc}
     */
    protected function readModelsFromWriteModel(WriteModelInterface $writeModel)
    {
        /** @var PostEventSourced $aggregate */
        $aggregate = $writeModel;

        return array(
            new PublishedPost(
                $aggregate->id(),
                $aggregate->title()
            ),
        );
    }

    /**
     * @return string
     */
    protected function writeModelClass()
    {
        return PostEventSourced::class;
    }
}
