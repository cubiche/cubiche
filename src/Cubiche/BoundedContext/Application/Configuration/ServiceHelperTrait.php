<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application\Configuration;

use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\EventSourcing\AggregateRepository;
use Cubiche\Domain\ProcessManager\ProcessManager;
use Cubiche\Domain\Repository\Factory\QueryRepositoryFactoryInterface;
use Cubiche\Domain\Repository\Factory\RepositoryFactoryInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\Repository\RepositoryInterface;
use Psr\Container\ContainerInterface;

/**
 * ServiceHelper trait.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait ServiceHelperTrait
{
    /**
     * @param string $name
     *
     * @return RepositoryInterface
     */
    protected function commandRepository($name)
    {
        return $this->get($this->getServiceAlias('command_repository', $name));
    }

    /**
     * @param string $name
     *
     * @return QueryRepositoryInterface
     */
    protected function queryRepository($name)
    {
        return $this->get($this->getServiceAlias('query_repository', $name));
    }

    /**
     * @param string $name
     *
     * @return AggregateRepository
     */
    protected function aggregateRepository($name)
    {
        return $this->get($this->getServiceAlias('aggregate_repository', $name));
    }

    /**
     * @param string $name
     *
     * @return QueryRepositoryInterface
     */
    protected function modelRepository($name)
    {
        return $this->get($this->getServiceAlias('model_repository', $name));
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function service($name)
    {
        return $this->get($this->getServiceAlias('service', $name));
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function parameter($name)
    {
        return $this->get($this->getServiceAlias('parameter', $name));
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function factory($name)
    {
        return $this->get($this->getServiceAlias('factory', $name));
    }

    /**
     * @param string $name
     *
     * @return DomainEventSubscriberInterface
     */
    protected function projector($name)
    {
        return $this->get($this->getServiceAlias('projector', $name));
    }

    /**
     * @param string $name
     *
     * @return ProcessManager
     */
    protected function processManager($name)
    {
        return $this->get($this->getServiceAlias('process_manager', $name));
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function commandHandler($name)
    {
        return $this->get($this->getServiceAlias('command_handler', $name));
    }

    /**
     * @return array
     */
    protected function commandHandlers()
    {
        return $this->get('app.command_bus.handlers');
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function queryHandler($name)
    {
        return $this->get($this->getServiceAlias('query_handler', $name));
    }

    /**
     * @return array
     */
    protected function queryHandlers()
    {
        return $this->get('app.query_bus.handlers');
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function eventHandler($name)
    {
        return $this->get($this->getServiceAlias('event_handler', $name));
    }

    /**
     * @return array
     */
    protected function eventHandlers()
    {
        return $this->get('app.event_bus.handlers');
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function eventSubscriber($name)
    {
        return $this->get($this->getServiceAlias('event_subscriber', $name));
    }

    /**
     * @return array
     */
    protected function eventSubscribers()
    {
        return $this->get('app.event_bus.subscribers');
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function validator($name)
    {
        return $this->get($this->getServiceAlias('validator', $name));
    }

    /**
     * @return array
     */
    protected function validators()
    {
        return $this->get('app.validator.asserters');
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function commandRepositoryAlias($name)
    {
        return $this->getServiceAlias('command_repository', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function queryRepositoryAlias($name)
    {
        return $this->getServiceAlias('query_repository', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function aggregateRepositoryAlias($name)
    {
        return $this->getServiceAlias('aggregate_repository', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function modelRepositoryAlias($name)
    {
        return $this->getServiceAlias('model_repository', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function serviceAlias($name)
    {
        return $this->getServiceAlias('service', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function parameterAlias($name)
    {
        return $this->getServiceAlias('parameter', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function factoryAlias($name)
    {
        return $this->getServiceAlias('factory', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function projectorAlias($name)
    {
        return $this->getServiceAlias('projector', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function processManagerAlias($name)
    {
        return $this->getServiceAlias('process_manager', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function commandHandlerAlias($name)
    {
        return $this->getServiceAlias('command_handler', $name);
    }

    /**
     * @return string
     */
    protected function commandHandlersAlias()
    {
        return 'app.command_bus.handlers';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function queryHandlerAlias($name)
    {
        return $this->getServiceAlias('query_handler', $name);
    }

    /**
     * @return string
     */
    protected function queryHandlersAlias()
    {
        return 'app.query_bus.handlers';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function eventHandlerAlias($name)
    {
        return $this->getServiceAlias('event_handler', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function eventSubscriberAlias($name)
    {
        return $this->getServiceAlias('event_subscriber', $name);
    }

    /**
     * @return string
     */
    protected function eventSubscribersAlias()
    {
        return 'app.event_bus.subscribers';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function validatorAlias($name)
    {
        return $this->getServiceAlias('validator.asserter', $name);
    }

    /**
     * @return string
     */
    protected function validatorsAlias()
    {
        return 'app.validator.asserters';
    }

    /**
     * @return string
     */
    protected function commandBusAlias()
    {
        return 'app.command_bus';
    }

    /**
     * @return string
     */
    protected function queryBusAlias()
    {
        return 'app.query_bus';
    }

    /**
     * @return string
     */
    protected function eventBusAlias()
    {
        return 'app.event_bus';
    }

    /**
     * @param ContainerInterface $container
     * @param string             $aggregateName
     *
     * @return RepositoryInterface
     */
    protected function createEventSourcedAggregateRepository(ContainerInterface $container, $aggregateName)
    {
        /** @var RepositoryFactoryInterface $factory */
        $factory = $container->get('app.repository.factory.event_sourced_aggregate');

        return $factory->create($aggregateName);
    }

    /**
     * @param ContainerInterface $container
     * @param string             $aggregateName
     *
     * @return RepositoryInterface
     */
    protected function createAggregateRepository(ContainerInterface $container, $aggregateName)
    {
        /** @var RepositoryFactoryInterface $factory */
        $factory = $container->get('app.repository.factory.aggregate');

        return $factory->create($aggregateName);
    }

    /**
     * @param ContainerInterface $container
     * @param string             $modelName
     *
     * @return QueryRepositoryInterface
     */
    protected function createModelRepository(ContainerInterface $container, $modelName)
    {
        /** @var QueryRepositoryFactoryInterface $factory */
        $factory = $container->get('app.repository.factory.model');

        return $factory->create($modelName);
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return string
     */
    protected function getServiceAlias($type, $name = null)
    {
        $namespace = null;
        if ($position = strrpos($name, '.')) {
            $namespace = substr($name, 0, $position);
            $name = substr($name, $position + 1);
        }

        if ($name !== null) {
            return sprintf('%s.%s.%s', $namespace ?? $this->getNamespace(), $type, $name);
        }

        return sprintf('%s.%s', $namespace ?? $this->getNamespace(), $type);
    }
}
