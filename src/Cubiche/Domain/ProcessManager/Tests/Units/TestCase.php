<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Units;

use Closure;
use Cubiche\Core\Validator\Validator;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Command\CreateConferenceCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Conference;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceCommandHandler;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\CreateOrderCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\MarkOrderAsBookedCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command\RejectOrderCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Order;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\OrderCommandHandler;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager\OrderProcessManager;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager\OrderProcessState;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CancelSeatReservationCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CommitSeatReservationCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CreateSeatsAvailabilityCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\MakeSeatReservationCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\SeatsAvailability;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\SeatsAvailabilityCommandHandler;
use Cubiche\Tests\TestCase as BaseTestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * TestCase class.
 *
 * Generated by TestGenerator on 2018-02-22 at 09:42:04.
 */
abstract class TestCase extends BaseTestCase
{
    use SettingCommandBusTrait;
    use SettingWriteRepositoryTrait;
    use SettingQueryRepositoryTrait;
    use SettingEventBusTrait;

    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param Closure   $reflectionClassFactory
     * @param Closure   $phpExtensionFactory
     * @param Analyzer  $analyzer
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        Closure $reflectionClassFactory = null,
        Closure $phpExtensionFactory = null,
        Analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );

        $this->getAsserterGenerator()->addNamespace('Cubiche\Core\Equatable\Tests\Asserters');
        $this->getAsserterGenerator()->addNamespace('Cubiche\Core\Collections\Tests\Asserters');

        $this->getAssertionManager()->setAlias('variable', 'VariableAsserter');
        $this->getAssertionManager()->setAlias('collection', 'CollectionAsserter');
        $this->getAssertionManager()->setAlias('list', 'ListAsserter');
        $this->getAssertionManager()->setAlias('set', 'SetAsserter');
        $this->getAssertionManager()->setAlias('hashmap', 'HashMapAsserter');
        $this->getAssertionManager()->setAlias('datasource', 'DataSourceAsserter');

        Validator::registerValidator('Cubiche\\Domain\\Geolocation\\Validation\\Rules', true);
        Validator::registerValidator('Cubiche\\Domain\\Identity\\Validation\\Rules', true);
        Validator::registerValidator('Cubiche\\Domain\\Locale\\Validation\\Rules', true);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeTestMethod($method)
    {
        DomainEventPublisher::set($this->eventBus());
    }

    /**
     * @return array
     */
    protected function commandHandlers()
    {
        $conferenceCommandHandler = new ConferenceCommandHandler(
            $this->writeRepository(Conference::class)
        );

        $orderCommandHandler = new OrderCommandHandler(
            $this->writeRepository(Order::class)
        );

        $seatsAvailabilityCommandHandler = new SeatsAvailabilityCommandHandler(
            $this->writeRepository(SeatsAvailability::class)
        );

        return array(
            CreateConferenceCommand::class => $conferenceCommandHandler,
            CreateOrderCommand::class => $orderCommandHandler,
            MarkOrderAsBookedCommand::class => $orderCommandHandler,
            RejectOrderCommand::class => $orderCommandHandler,
            CreateSeatsAvailabilityCommand::class => $seatsAvailabilityCommandHandler,
            CancelSeatReservationCommand::class => $seatsAvailabilityCommandHandler,
            CommitSeatReservationCommand::class => $seatsAvailabilityCommandHandler,
            MakeSeatReservationCommand::class => $seatsAvailabilityCommandHandler,
        );
    }

    /**
     * @return array
     */
    protected function commandValidatorHandlers()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function eventSubscribers()
    {
        $orderProcessManager = new OrderProcessManager(
            $this->commandBus(),
            $this->queryRepository(OrderProcessState::class)
        );

        return array(
            $orderProcessManager,
        );
    }
}
