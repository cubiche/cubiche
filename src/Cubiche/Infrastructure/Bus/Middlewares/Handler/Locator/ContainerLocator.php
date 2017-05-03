<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Bus\Middlewares\Handler\Locator;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\Handler\Locator\LocatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ContainerLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ContainerLocator implements LocatorInterface
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
     * @param string $nameOfMessage
     * @param object $handler
     */
    public function addHandler($nameOfMessage, $handler)
    {
        if (!is_string($nameOfMessage)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an string as a name of message. Instance of %s given',
                is_object($nameOfMessage) ? get_class($nameOfMessage) : gettype($nameOfMessage)
            ));
        }

        if (!is_string($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an string as handler. Instance of %s given',
                is_object($nameOfMessage) ? get_class($nameOfMessage) : gettype($nameOfMessage)
            ));
        }

        $this->messageToServiceId[$nameOfMessage] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function locate($nameOfMessage)
    {
        if (!isset($this->messageToServiceId[$nameOfMessage])) {
            throw NotFoundException::handlerFor($nameOfMessage);
        }

        return $this->container->get($this->messageToServiceId[$nameOfMessage]);
    }
}
