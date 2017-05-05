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

/**
 * Projection class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Projection
{
    /**
     * @var ReadModelInterface
     */
    protected $readModel;

    /**
     * @var Action
     */
    protected $action;

    /**
     * Projection constructor.
     *
     * @param ReadModelInterface $readModel
     * @param Action             $action
     */
    public function __construct(ReadModelInterface $readModel, Action $action)
    {
        $this->readModel = $readModel;
        $this->action = $action;
    }

    /**
     * @return ReadModelInterface
     */
    public function readModel()
    {
        return $this->readModel;
    }

    /**
     * @param ReadModelInterface $readModel
     */
    public function setReadModel(ReadModelInterface $readModel)
    {
        $this->readModel = $readModel;
    }

    /**
     * @return Action
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * @param Action $action
     */
    public function setAction(Action $action)
    {
        $this->action = $action;
    }
}
