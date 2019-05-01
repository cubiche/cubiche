<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Visitor;

use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Context\DeserializationContext;
use Cubiche\Core\Serializer\Context\SerializationContext;
use Cubiche\Core\Serializer\Event\PostDeserializeEvent;
use Cubiche\Core\Serializer\Event\PostSerializeEvent;
use Cubiche\Core\Serializer\Event\PreDeserializeEvent;
use Cubiche\Core\Serializer\Event\PreSerializeEvent;
use Cubiche\Core\Serializer\Exception\SerializationException;
use Cubiche\Core\Serializer\Handler\HandlerManagerInterface;
use RuntimeException;

/**
 * VisitorNavigator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class VisitorNavigator implements VisitorNavigatorInterface
{
    /**
     * @var HandlerManagerInterface
     */
    protected $handlerManager;

    /**
     * @var EventBus
     */
    protected $eventBus;

    /**
     * VisitorNavigator constructor.
     *
     * @param HandlerManagerInterface $handlerManager
     * @param EventBus                $eventBus
     */
    public function __construct(HandlerManagerInterface $handlerManager, EventBus $eventBus)
    {
        $this->handlerManager = $handlerManager;
        $this->eventBus = $eventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function accept($data, array $type = null, ContextInterface $context)
    {
        $visitor = $context->visitor();

        // If the type was not given, we infer the most specific type from the
        // input data in serialization mode.
        if (null === $type) {
            if ($context instanceof DeserializationContext) {
                throw new RuntimeException('The type must be given for all properties when deserializing.');
            }

            $typeName = is_object($data) ? get_class($data) : gettype($data);
            $type = array('name' => $typeName, 'params' => array());
        } elseif ($context instanceof SerializationContext && null === $data) {
            // If the data is null, we have to force the type to null regardless of the input in order to
            // guarantee correct handling of null values, and not have any internal auto-casting behavior.
            $type = array('name' => 'NULL', 'params' => array());
        }

        // Sometimes data can convey null but is not of a null type.
        // Visitors can have the power to add this custom null evaluation
        if ($visitor->isNull($data) === true) {
            $type = array('name' => 'NULL', 'params' => array());
        }

        // collection types
        if (preg_match('/(.+)\\[(.+)\\]/', $type['name'], $output) === 1) {
            $type['name'] = $output[1];
            $types = explode(',', $output[2]);

            if ($type['name'] === 'hashmap') {
                $type['params'] = $this->getHashmapTypes($types);
            } else {
                foreach ($types as $typeName) {
                    $type['params'][] = array('name' => $typeName, 'params' => array());
                }
            }
        }

        switch ($type['name']) {
            case 'NULL':
                return $visitor->visitNull($data, $type, $context);
            case 'string':
                return $visitor->visitString($data, $type, $context);
            case 'int':
            case 'integer':
                return $visitor->visitInteger($data, $type, $context);
            case 'bool':
            case 'boolean':
                return $visitor->visitBoolean($data, $type, $context);
            case 'double':
            case 'float':
                return $visitor->visitDouble($data, $type, $context);
            case 'array':
                return $visitor->visitArray($data, $type, $context);
            case 'hashmap':
                return $visitor->visitHashmap($data, $type, $context);
            case 'resource':
                throw new RuntimeException('Resources are not supported in serialized data.');
            default:
                // dispatching pre serialize/deserialize event
                $this->dispatchPreEvent($data, $type, $context);

                $handler = $this->handlerManager->handler($type['name'], $context);
                if ($handler === null) {
                    throw SerializationException::notHandler($type['name']);
                }

                if ($context instanceof SerializationContext) {
                    $result = $handler->serialize($visitor, $data, $type, $context);
                } else {
                    $result = $handler->deserialize($visitor, $data, $type, $context);
                }

                // dispatching post serialize/deserialize event
                $this->dispatchPostEvent($result, $type, $context);

                return $result;
        }
    }

    /**
     * @param                  $data
     * @param array            $type
     * @param ContextInterface $context
     */
    protected function dispatchPreEvent($data, array $type, ContextInterface $context)
    {
        if ($context instanceof SerializationContext) {
            $event = new PreSerializeEvent($context, $data, $type);
        } else {
            $event = new PreDeserializeEvent($context, $data, $type);
        }

        $this->eventBus->dispatch($event);
    }

    /**
     * @param                  $object
     * @param array            $type
     * @param ContextInterface $context
     */
    protected function dispatchPostEvent($object, array $type, ContextInterface $context)
    {
        if ($context instanceof SerializationContext) {
            $event = new PostSerializeEvent($context, $object, $type);
        } else {
            $event = new PostDeserializeEvent($context, $object, $type);
        }

        $this->eventBus->dispatch($event);
    }

    private function getHashmapTypes(array $types): array
    {
        $result = [];
        foreach ($types as $typeName) {
            $pieces = explode(':', $typeName);
            if (count($pieces) !== 2) {
                throw new \LogicException(
                    'The mapping hashmap type it must has only two types. hashmap[foo:integer,bar:bool]'
                );
            }

            $result[$pieces[0]] = array('name' => $pieces[1], 'params' => array());
        }

        return $result;
    }
}
