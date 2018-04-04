<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder\Handler;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Encoder\Context\ContextInterface;
use Cubiche\Core\Encoder\Context\SerializationContext;
use RuntimeException;

/**
 * HandlerManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HandlerManager implements HandlerManagerInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $handlers;

    /**
     * HandlerManager constructor.
     */
    public function __construct()
    {
        $this->handlers = new ArrayHashMap();
    }

    /**
     * {@inheritdoc}
     */
    public function registerSubscriberHandler(HandlerSubscriberInterface $subscriberHandler)
    {
        $subscriberHandlers = $subscriberHandler->getSubscribedHandlers();
        if (!isset($subscriberHandlers['serializers']) || !isset($subscriberHandlers['deserializers'])) {
            throw new RuntimeException('There is not serializers or deserializers handlers definitions.');
        }

        if (isset($subscriberHandlers['serializers'])) {
            foreach ($subscriberHandlers['serializers'] as $typeName => $methodName) {
                if (!is_string($methodName)) {
                    throw new RuntimeException('Invalid method name definition.');
                }

                $this->addHandler('serializer.'.$typeName, array($subscriberHandler, $methodName));
            }
        }

        if (isset($subscriberHandlers['deserializers'])) {
            foreach ($subscriberHandlers['deserializers'] as $typeName => $methodName) {
                if (!is_string($methodName)) {
                    throw new RuntimeException('Invalid method name definition.');
                }

                $this->addHandler('deserializer.'.$typeName, array($subscriberHandler, $methodName));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handler($typeName, ContextInterface $context)
    {
        if ($context instanceof SerializationContext) {
            return $this->handlers->get('serializer.'.$typeName);
        }

        return $this->handlers->get('deserializer.'.$typeName);
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandler($typeName, ContextInterface $context)
    {
        if ($context instanceof SerializationContext) {
            return $this->handlers->containsKey('serializer.'.$typeName);
        }

        return $this->handlers->containsKey('deserializer.'.$typeName);
    }

    /**
     * Adds an type handler.
     *
     * @param string   $typeName
     * @param callable $handler
     */
    protected function addHandler($typeName, callable $handler)
    {
        $this->handlers->set($typeName, new Delegate($handler));
    }
}
