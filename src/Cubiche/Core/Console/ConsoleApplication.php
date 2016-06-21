<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console;

use Cubiche\Core\Bus\Command\CommandBus;
use Cubiche\Core\Bus\Event\EventBus;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Console\Api\Config\CommandConfig;
use Cubiche\Core\Console\Converter\ConsoleArgsToCommand;
use Cubiche\Core\Console\Handler\DefaultEventHandler;
use Webmozart\Assert\Assert;
use Webmozart\Console\Api\Config\ApplicationConfig;
use Webmozart\Console\Api\Event\ConsoleEvents;
use Webmozart\Console\Api\Event\PreHandleEvent;
use Webmozart\Console\ConsoleApplication as BaseConsoleApplication;

/**
 * ConsoleApplication class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ConsoleApplication extends BaseConsoleApplication
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var EventBus
     */
    protected $eventBus;

    /**
     * @var DefaultEventHandler
     */
    protected $defaultHandler;

    /**
     * @var ConsoleArgsToCommand
     */
    protected $commandConverter;

    /**
     * ConsoleApplication constructor.
     *
     * @param callable|ApplicationConfig $config
     * @param CommandBus                 $commandBus
     * @param EventBus                   $eventBus
     */
    public function __construct($config, CommandBus $commandBus, EventBus $eventBus)
    {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->defaultHandler = new DefaultEventHandler();
        $this->commandConverter = new ConsoleArgsToCommand();

        parent::__construct($this->normalizeConfig($config));
    }

    /**
     * @param ApplicationConfig|callable $config
     *
     * @return ApplicationConfig|callable
     */
    protected function normalizeConfig($config)
    {
        if (is_callable($config)) {
            /** @var ApplicationConfig $configuration */
            $configuration = $config();
        } else {
            $configuration = $config;
        }

        Assert::isInstanceOf(
            $configuration,
            'Webmozart\Console\Api\Config\ApplicationConfig',
            'The $config argument must be an ApplicationConfig or a callable. Got: %s'
        );

        // add listener
        $configuration->addEventListener(ConsoleEvents::PRE_HANDLE, function (PreHandleEvent $event) {
            $this->onPreHandle($event);
        });

        return $configuration;
    }

    /**
     * @param PreHandleEvent $event
     */
    protected function onPreHandle(PreHandleEvent $event)
    {
        // convert console args to command
        $this->commandConverter->setArgs($event->getArgs());
        $this->commandConverter->setFormat($event->getCommand()->getArgsFormat());

        /** @var CommandConfig $commandConfig */
        $commandConfig = $event->getCommand()->getConfig();
        if ($event->getCommand()->getName() !== 'help') {
            if ($commandConfig->className() === null && count($commandConfig->getSubCommandConfigs()) === 0) {
                throw new \RuntimeException(sprintf(
                    'The command %s definition must set the className using ->setClass() method',
                    $event->getCommand()->getName()
                ));
            }
        }

        $command = $this->commandConverter->getCommandFrom($commandConfig->className());

        $this->defaultHandler->setIo($event->getIO());
        $this->defaultHandler->clearEventHandlers();

        if ($commandConfig->preDispatchEventHandler() !== null) {
            $this->defaultHandler->addPreDispatchEventHandler($commandConfig->preDispatchEventHandler());
        }

        if ($commandConfig->postDispatchEventHandler()) {
            $this->defaultHandler->addPostDispatchEventHandler($commandConfig->postDispatchEventHandler());
        }

        $commandConfig->setEventBus($this->eventBus);
        $commandConfig->addEventSubscriber($this->defaultHandler);

        if ($command !== null) {
            try {
                $this->commandBus->dispatch($command);

                $event->setHandled(true);
                $event->setStatusCode(0);
            } catch (NotFoundException $e) {
            }
        } else {
            if ($event->getCommand()->getName() !== 'help') {
                $helpCommand = $this->getCommand('help');

                $args = $event->getArgs();
                $args = $helpCommand->parseArgs($args->getRawArgs());
                $args->setArgument('command', $event->getCommand()->getName());

                $helpConfig = $this->getConfig()->getCommandConfig('help');

                $commandHandler = $helpConfig->getHandler();
                $handlerMethod = $helpConfig->getHandlerMethod();

                $commandHandler->$handlerMethod($args, $event->getIO(), $helpCommand);

                $event->setHandled(true);
                $event->setStatusCode(0);
            }
        }
    }
}
