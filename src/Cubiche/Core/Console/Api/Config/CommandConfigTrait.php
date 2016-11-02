<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Console\Api\Config;

use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\EventBus\Event\EventSubscriberInterface;

/**
 * CommandConfig trait.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait CommandConfigTrait
{
    /**
     * @var EventBus
     */
    protected $eventBus;

    /**
     * @var callback
     */
    protected $preDispatchHandler = null;

    /**
     * @var callback
     */
    protected $postDispatchHandler = null;

    /**
     * @var string
     */
    protected $className;

    /**
     * @param EventBus $eventBus
     */
    public function setEventBus(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @param string $className
     *
     * @return $this
     */
    public function setClass($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @param callable $handler
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function onPreDispatchEvent($handler)
    {
        if (!is_callable($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected a callable. Got: %s',
                is_object($handler) ? get_class($handler) : gettype($handler)
            ));
        }

        $this->preDispatchHandler = $handler;

        return $this;
    }

    /**
     * @param callable $handler
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function onPostDispatchEvent($handler)
    {
        if (!is_callable($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected a callable. Got: %s',
                is_object($handler) ? get_class($handler) : gettype($handler)
            ));
        }

        $this->postDispatchHandler = $handler;

        return $this;
    }

    /**
     * @return callable
     */
    public function preDispatchEventHandler()
    {
        return $this->preDispatchHandler;
    }

    /**
     * @return callable
     */
    public function postDispatchEventHandler()
    {
        return $this->postDispatchHandler;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     *
     * @return $this
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        if ($this->eventBus === null) {
            throw new \RuntimeException('The event bus should not be null');
        }

        $this->eventBus->addSubscriber($subscriber);

        return $this;
    }
}
