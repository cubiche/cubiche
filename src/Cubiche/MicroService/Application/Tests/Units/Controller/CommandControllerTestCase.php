<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Tests\Units\Controllers;

use Cubiche\MicroService\Application\Controllers\CommandController;
use Cubiche\MicroService\Application\Tests\Units\TestCase;
use Cubiche\Core\Bus\Command\CommandBus;

/**
 * CommandControllerTestCase class.
 */
abstract class CommandControllerTestCase extends TestCase
{
    /**
     * @return CommandController
     */
    abstract protected function createController();

    /**
     * Test CommandBus method.
     */
    public function testCommandBus()
    {
        $this
            ->given($controller = $this->createController())
            ->then()
                ->object($controller->commandBus())
                    ->isInstanceOf(CommandBus::class)
        ;
    }
}
