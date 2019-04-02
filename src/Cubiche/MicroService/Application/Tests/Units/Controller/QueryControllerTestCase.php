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

use Cubiche\MicroService\Application\Controllers\QueryController;
use Cubiche\MicroService\Application\Tests\Units\TestCase;
use Cubiche\Core\Cqrs\Query\QueryBus;

/**
 * QueryControllerTestCase class.
 */
abstract class QueryControllerTestCase extends TestCase
{
    /**
     * @return QueryController
     */
    abstract protected function createController();

    /**
     * Test QueryBus method.
     */
    public function testQueryBus()
    {
        $this
            ->given($controller = $this->createController())
            ->then()
                ->object($controller->queryBus())
                    ->isInstanceOf(QueryBus::class)
        ;
    }
}
