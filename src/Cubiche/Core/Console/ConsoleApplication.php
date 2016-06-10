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

use Cubiche\Core\Bus\Event\EventBus;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Command\CommandBus;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\Command\Command;
use Webmozart\Console\Api\Config\ApplicationConfig;
use Webmozart\Console\Api\Event\ConsoleEvents;
use Webmozart\Console\Api\Event\PreHandleEvent;
use Webmozart\Console\ConsoleApplication as BaseConsoleApplication;
use Webmozart\Assert\Assert;

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
     * ConsoleApplication constructor.
     *
     * @param callable|ApplicationConfig $config
     * @param CommandBus                 $commandBus
     * @param EventBus                   $eventBus
     */
    public function __construct($config, CommandBus $commandBus, EventBus $eventBus = null)
    {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;

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
        $configuration->addEventListener(ConsoleEvents::PRE_HANDLE, function(PreHandleEvent $event) {
            $this->onPreHandle($event);
        });
//
//        foreach ($configuration->getCommandConfigs() as $commandConfig) {
////            print_r($commandConfig->getName()."\n");
//
//            try {
//                $handler = $this->commandBus->getHandlerFor($commandConfig->getName());
//                $handlerMethod = $this->commandBus->getHandlerMethodFor($commandConfig->getName());
//            } catch (NotFoundException $e) {
//                $handler = $commandConfig->getHandler();
//                $handlerMethod = $commandConfig->getHandlerMethod();
//            }
//
//            // replace the handler and handlerMethod for the correct registered in the command bus
//            $commandConfig->setHandler($handler);
//            $commandConfig->setHandlerMethod($handlerMethod);
//
////            print_r(get_class($handler)."\n");
////            print_r($handlerMethod."\n");
////            foreach ($commandConfig->getSubCommandConfigs() as $subCommandConfig) {
////                die(var_export($subCommandConfig));
////            }
////            //die(var_export($commandConfig->getName()));
//        }

        return $configuration;
    }

    /**
     * @param PreHandleEvent $event
     */
    protected function onPreHandle(PreHandleEvent $event)
    {
        $command = $this->createBusCommand($event->getCommand(), $event->getArgs());
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

    /**
     * @param Command $command
     * @param Args    $args
     *
     * @return object
     */
    protected function createBusCommand(Command $command, Args $args)
    {
        $className = $command->getName();

        if (class_exists($className)) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $reflector = new \ReflectionClass($className);
            $instance = $reflector->newInstanceWithoutConstructor();

            foreach ($reflector->getProperties() as $property) {
                if (!$command->getArgsFormat()->hasArgument($property->getName())) {
                    throw new \InvalidArgumentException(sprintf(
                        "There is not '%s' argument defined in the %s command",
                        $property->getName(),
                        $className
                    ));
                }

                $accessor->setValue($instance, $property->getName(), $args->getArgument($property->getName()));
            }

            return $instance;
        }

        return null;
    }
}
