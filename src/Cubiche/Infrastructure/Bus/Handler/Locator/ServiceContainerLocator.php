<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Bus\Handler\Locator;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Handler\Locator\HandlerLocatorInterface;
use Psr\Container\ContainerInterface;

/**
 * ContainerLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ServiceContainerLocator implements HandlerLocatorInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $messageToServiceId = [];

    /**
     * ContainerLocator constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->messageToServiceId = array();
    }

    /**
     * @param string $messageName
     * @param object $handler
     */
    public function addHandler(string $messageName, $handler)
    {
        if (!is_string($messageName)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an string as a name of message. Instance of %s given',
                is_object($messageName) ? get_class($messageName) : gettype($messageName)
            ));
        }

        if (!is_string($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an string as handler. Instance of %s given',
                is_object($messageName) ? get_class($messageName) : gettype($messageName)
            ));
        }

        $this->messageToServiceId[$messageName] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function locate(string $messageName)
    {
        if (!isset($this->messageToServiceId[$messageName])) {
            throw NotFoundException::handlerFor($messageName);
        }

        if (!$this->container->has($this->messageToServiceId[$messageName])) {
            throw NotFoundException::handlerFor($this->messageToServiceId[$messageName]);
        }

        return $this->container->get($this->messageToServiceId[$messageName]);
    }
}
