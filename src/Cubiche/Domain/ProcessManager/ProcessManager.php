<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Cqrs\Command\CommandBus;
use Cubiche\Core\EventBus\Event\Event;
use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use SM\StateMachine\StateMachine;

/**
 * ProcessManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class ProcessManager
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @var QueryRepositoryInterface
     */
    private $repository;

    /**
     * @var ProcessManagerConfig
     */
    private $config;

    /**
     * ProcessManager constructor.
     *
     * @param CommandBus                    $commandBus
     * @param EventBus                      $eventBus
     * @param QueryRepositoryInterface|null $repository
     */
    public function __construct(
        CommandBus $commandBus,
        EventBus $eventBus,
        QueryRepositoryInterface $repository = null
    ) {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->repository = $repository;

        $this->config = new ProcessManagerConfig();
        $this->build($this->config);
    }

    /**
     * @param IdInterface $stateId
     *
     * @return ProcessManagerState
     *
     * @throws \Exception
     */
    public function load(IdInterface $stateId)
    {
        /** @var ProcessManagerState $state */
        $state = $this->repository()->get($stateId);
        if ($state === null) {
            throw new \InvalidArgumentException(sprintf(
                'There is no process manager state with id: %s',
                $stateId->toNative()
            ));
        }

        return $state;
    }

    /**
     * @param ProcessManagerState $state
     */
    public function persist(ProcessManagerState $state)
    {
        $this->repository()->persist($state);
    }

    /**
     * @param string              $transition
     * @param ProcessManagerState $state
     *
     * @throws \Exception
     */
    public function apply($transition, ProcessManagerState $state)
    {
        $stateMachine = new StateMachine($state, $this->configuration());
        if (!$stateMachine->can($transition)) {
            throw new \LogicException('Invalid transition operation');
        }

        $stateMachine->apply($transition);
    }

    /**
     * @param Command $command
     */
    public function dispatch(Command $command)
    {
        $this->commandBus->dispatch($command);
    }

    /**
     * @param Event $event
     */
    public function trigger(Event $event)
    {
        $this->eventBus->dispatch($event);
    }

    /**
     * @return QueryRepositoryInterface
     *
     * @throws \Exception
     */
    private function repository()
    {
        if ($this->repository === null) {
            throw new \BadMethodCallException('There is not repository defined.');
        }

        return $this->repository;
    }

    /**
     * @return array
     */
    private function configuration()
    {
        return array_merge(
            array(
                'graph' => $this->name(),
                'property_path' => 'state',
            ),
            $this->config->toArray()
        );
    }

    /**
     * @param ProcessManagerConfig $config
     */
    protected function build(ProcessManagerConfig $config)
    {
    }

    /**
     * @return string
     */
    abstract protected function name();
}
