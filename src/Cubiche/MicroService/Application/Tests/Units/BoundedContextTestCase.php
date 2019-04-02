<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Tests\Units;

use Cubiche\BoundedContext\Application\Tests\Units\BoundedContextTestCase as BaseBoundedContextTestCase;
use Cubiche\MicroService\Application\BoundedContextInterface;
use Cubiche\MicroService\Application\Configuration\ServiceHelperTrait;
use Cubiche\MicroService\Application\Controllers\CommandController;
use Cubiche\MicroService\Application\Controllers\QueryController;
use Cubiche\MicroService\Application\Services\TokenContextInterface;
use Cubiche\MicroService\Application\Services\TokenManager;
use Cubiche\MicroService\Application\Tests\Fixtures\TokenContext;

/**
 * BoundedContextTestCase class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class BoundedContextTestCase extends BaseBoundedContextTestCase
{
    use ServiceHelperTrait;

    /**
     * @param string $name
     *
     * @return CommandController
     */
    public function commandController($name)
    {
        /** @var BoundedContextInterface $context */
        $context = $this->context;

        return $context->commandController($name);
    }

    /**
     * @param string $name
     *
     * @return QueryController
     */
    public function queryController($name)
    {
        /** @var BoundedContextInterface $context */
        $context = $this->context;

        return $context->queryController($name);
    }

    /**
     * @return TokenManager
     */
    protected function tokenManager()
    {
        $ref = new \ReflectionMethod($this->context, 'tokenManager');
        $ref->setAccessible(true);

        return $ref->invoke($this->context);
    }

    /**
     * @return TokenContextInterface
     */
    protected function tokenContext()
    {
        $ref = new \ReflectionMethod($this->context, 'tokenContext');
        $ref->setAccessible(true);

        return $ref->invoke($this->context);
    }

    /**
     * @return TokenContextInterface
     */
    protected function emptyTokenContext()
    {
        return new TokenContext($this->tokenManager());
    }
}
