<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\GraphQL;

use Cubiche\MicroService\Application\BoundedContextInterface;
use Cubiche\MicroService\Application\Exception\ServiceNotFoundException;
use Youshido\GraphQL\Execution\Container\ContainerInterface;

/**
 * ApplicationContainer class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ApplicationContainer implements ContainerInterface
{
    /**
     * @var BoundedContextInterface
     */
    protected $boundedContext;

    /**
     * Container constructor.
     *
     * @param BoundedContextInterface $boundedContext
     */
    public function __construct(BoundedContextInterface $boundedContext)
    {
        $this->boundedContext = $boundedContext;
    }

    /**
     * {@inheritdoc}
     */
    public function get($serviceId)
    {
        // at this point we can access just to controllers
        $controllerName = substr($serviceId, strrpos($serviceId, '.') + 1);
        if (strpos($serviceId, 'query_controller') !== false) {
            return $this->boundedContext->queryController($controllerName);
        } elseif (strpos($serviceId, 'command_controller') !== false) {
            return $this->boundedContext->commandController($controllerName);
        }

        throw new ServiceNotFoundException(sprintf("No entry or class found for '%s'", $serviceId));
    }

    /**
     * {@inheritdoc}
     */
    public function set($serviceId, $value)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function remove($serviceId)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function has($serviceId)
    {
        return;
    }
}
