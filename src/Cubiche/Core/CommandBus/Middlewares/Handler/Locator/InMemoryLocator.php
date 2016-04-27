<?php
/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Middlewares\Handler\Locator;

use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\CommandBus\Exception\NotFoundException;

/**
 * InMemoryLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryLocator implements LocatorInterface
{
    /**
     * @var ArrayCollection
     */
    protected $handlers;

    /**
     * InMemoryLocator constructor.
     *
     * @param array $commandClassToHandlerMap
     */
    public function __construct(array $commandClassToHandlerMap = [])
    {
        $this->handlers = new ArrayCollection();
        foreach ($commandClassToHandlerMap as $commandClass => $handler) {
            $this->add($commandClass, $handler);
        }
    }

    /**
     * @param string $commandClass
     * @param object $handler
     */
    public function add($commandClass, $handler)
    {
        if (!is_string($commandClass)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an string as a command name. Got: %s',
                is_object($commandClass) ? get_class($commandClass) : gettype($commandClass)
            ));
        }

        if (!is_object($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an object as handler. Got: %s',
                gettype($handler)
            ));
        }

        $this->handlers->set($commandClass, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function locate($commandName)
    {
        if (!$this->handlers->containsKey($commandName)) {
            throw NotFoundException::handlerForCommand($commandName);
        }

        return $this->handlers->get($commandName);
    }
}
