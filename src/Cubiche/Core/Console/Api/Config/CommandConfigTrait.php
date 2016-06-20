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

use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;

/**
 * CommandConfig trait.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait CommandConfigTrait
{
    /**
     * @var callback
     */
    protected $preDispatchHandler;

    /**
     * @var callback
     */
    protected $postDispatchHandler;

    /**
     * @var string
     */
    protected $className;

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
    public function onPreDispatch($handler)
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
    public function onPostDispatch($handler)
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
    public function preDispatchHandler()
    {
        return $this->preDispatchHandler;
    }

    /**
     * @return callable
     */
    public function postDispatchHandler()
    {
        return $this->postDispatchHandler;
    }

    /**
     * @param DomainEventSubscriberInterface $subscriber
     *
     * @return $this
     */
    public function addEventSubscriber(DomainEventSubscriberInterface $subscriber)
    {
        DomainEventPublisher::subscribe($subscriber);

        return $this;
    }
}
