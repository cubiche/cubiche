<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application;

use Cubiche\BoundedContext\Application\Configuration\ChainConfigurator;
use Cubiche\BoundedContext\Application\Configuration\CommandBusConfigurator;
use Cubiche\BoundedContext\Application\Configuration\ConfiguratorInterface;
use Cubiche\BoundedContext\Application\Configuration\CoreConfigurator;
use Cubiche\BoundedContext\Application\Configuration\EventBusConfigurator;
use Cubiche\BoundedContext\Application\Configuration\QueryBusConfigurator;
use Cubiche\BoundedContext\Application\Configuration\ServiceHelperTrait;
use Cubiche\BoundedContext\Application\Configuration\ValidatorConfigurator;
use Cubiche\Core\Cqrs\Command\CommandBus;
use Cubiche\Core\Cqrs\Query\QueryBus;
use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\Validator\Validator;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

/**
 * BoundedContext class.
 *
 * Sevice dependencies:
 *  - app.repository.factory.aggregate
 *  - app.repository.factory.model
 *  - app.event_store
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class BoundedContext implements BoundedContextInterface
{
    use ServiceHelperTrait;

    /**
     * @var ContainerBuilder
     */
    private $builder;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * BoundedContext constructor.
     *
     * @param ConfiguratorInterface|null $configurator
     */
    public function __construct(ConfiguratorInterface $configurator = null)
    {
        $this->builder = new ContainerBuilder();
        $this->builder->useAutowiring(false);
        $this->builder->useAnnotations(false);

        $this->configure($configurator);
        $this->container = $this->builder->build();
        $this->boot();
    }

    /**
     * @param ConfiguratorInterface|null $configurator
     */
    private function configure(ConfiguratorInterface $configurator = null)
    {
        $rootConfigurator = new ChainConfigurator(array(
            new CoreConfigurator(),
            new CommandBusConfigurator(),
            new QueryBusConfigurator(),
            new EventBusConfigurator(),
            new ValidatorConfigurator()
        ));

        $this->builder->addDefinitions($rootConfigurator->configuration());
        $this->builder->addDefinitions($this->configuration());

        if ($configurator !== null) {
            $this->builder->addDefinitions($configurator->configuration());
        }
    }

    /**
     * Force init critical services.
     */
    private function boot()
    {
        $this->validator();
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        return $this->container->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        return $this->container->has($name);
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @return CommandBus
     */
    protected function commandBus()
    {
        return $this->get('app.command_bus');
    }

    /**
     * @return QueryBus
     */
    protected function queryBus()
    {
        return $this->get('app.query_bus');
    }

    /**
     * @return EventBus
     */
    protected function eventBus()
    {
        return $this->get('app.event_bus');
    }

    /**
     * @return Validator
     */
    protected function validator()
    {
        return $this->get('app.validator');
    }
}
