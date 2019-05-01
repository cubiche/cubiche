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

use Cubiche\Core\Bus\Middlewares\ValidatorMiddleware;
use Cubiche\Core\Bus\Command\CommandBus;

/**
 * SettingCommandBus trait.
 *
 * Generated by TestGenerator on 2017-09-13 at 12:50:27.
 */
trait SettingCommandBusTrait
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @return CommandBus
     */
    public function commandBus()
    {
        if ($this->commandBus === null) {
            $this->commandBus = CommandBus::create();

            foreach ($this->commandHandlers() as $commandName => $commandHandler) {
                $this->commandBus->addHandler($commandName, $commandHandler);
            }

            $validatorMiddleware = null;
            foreach ($this->commandBus->middlewares() as $priority => $middlewares) {
                foreach ($middlewares as $middleware) {
                    if ($middleware instanceof ValidatorMiddleware) {
                        $validatorMiddleware = $middleware;
                        break;
                    }
                }
            }

            if ($validatorMiddleware) {
                foreach ($this->commandValidatorHandlers() as $commandName => $commandValidatorHandler) {
                    $validatorMiddleware->resolver()->addHandler($commandName, $commandValidatorHandler);
                }
            }
        }

        return $this->commandBus;
    }

    /**
     * @return array
     */
    abstract protected function commandHandlers();

    /**
     * @return array
     */
    abstract protected function commandValidatorHandlers();
}
