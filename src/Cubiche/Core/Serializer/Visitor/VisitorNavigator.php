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

use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Context\DeserializationContext;
use Cubiche\Core\Serializer\Context\SerializationContext;
use Cubiche\Core\Serializer\Event\PostDeserializeEvent;
use Cubiche\Core\Serializer\Event\PostSerializeEvent;
use Cubiche\Core\Serializer\Event\PreDeserializeEvent;
use Cubiche\Core\Serializer\Event\PreSerializeEvent;
use Cubiche\Core\Serializer\Exception\SerializationException;
use Cubiche\Core\Serializer\Handler\HandlerManagerInterface;
use Cubiche\Core\Serializer\SerializableInterface;
use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\ClassMetadataFactoryInterface;
use Cubiche\Domain\Model\NativeValueObjectInterface;
use RuntimeException;

/**
 * VisitorNavigator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class VisitorNavigator implements VisitorNavigatorInterface
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $metadataFactory;

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
     * @param ClassMetadataFactoryInterface $metadataFactory
     * @param HandlerManagerInterface       $handlerManager
     * @param EventBus                      $eventBus
     */
    public function __construct(
        ClassMetadataFactoryInterface $metadataFactory,
        HandlerManagerInterface $handlerManager,
        EventBus $eventBus
    ) {
        $this->metadataFactory = $metadataFactory;
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
            foreach ($types as $typeName) {
                $type['params'][] = array('name' => $typeName, 'params' => array());
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
            case 'resource':
                throw new RuntimeException('Resources are not supported in serialized data.');
            default:
                // dispatching pre serialize/deserialize event
                $this->dispatchPreEvent($data, $type, $context);

                // First, try whether a custom handler exists for the given type. This is done
                // before loading metadata because the type name might not be a class, but
                // could also simply be an artifical type.
                if ($this->handlerManager->hasHandler($type['name'], $context)) {
                    $handler = $this->handlerManager->handler($type['name'], $context);

                    return $handler($visitor, $data, $type, $context);
                }

                // check if the object is a native value object or serializable
                try {
                    $reflection = new \ReflectionClass($type['name']);

                    if ($reflection->implementsInterface(NativeValueObjectInterface::class)) {
                        return $visitor->visitNativeValueObject($data, $type, $context);
                    } elseif ($reflection->implementsInterface(SerializableInterface::class)) {
                        return $visitor->visitSerializable($data, $type, $context);
                    }
                } catch (\ReflectionException $exception) {
                }

                // get the class metadata
                $classMetadata = $this->getClassMetadata($type['name']);
                if ($classMetadata === null) {
                    throw SerializationException::invalidMapping($type['name']);
                }

                $object = $data;
                if ($context instanceof DeserializationContext) {
                    $object = $classMetadata->reflection()->newInstanceWithoutConstructor();
                }

                $visitor->startVisitingObject($classMetadata, $object, $type, $context);
                foreach ($classMetadata->propertiesMetadata() as $propertyMetadata) {
                    $visitor->visitProperty($propertyMetadata, $data, $context);
                }

                if ($context instanceof SerializationContext) {
                    $this->dispatchPostEvent($classMetadata, $data, $type, $context);

                    return $visitor->endVisitingObject($classMetadata, $data, $type, $context);
                }

                $result = $visitor->endVisitingObject($classMetadata, $data, $type, $context);
                $this->dispatchPostEvent($classMetadata, $result, $type, $context);

                return $result;
        }
    }

    /**
     * Returns the metadata for a class.
     *
     * @param string $className
     *
     * @return ClassMetadata
     */
    protected function getClassMetadata($className)
    {
        return $this->metadataFactory->getMetadataFor(ltrim($className, '\\'));
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
     * @param ClassMetadata    $metadata
     * @param                  $object
     * @param array            $type
     * @param ContextInterface $context
     */
    protected function dispatchPostEvent(ClassMetadata $metadata, $object, array $type, ContextInterface $context)
    {
        if ($context instanceof SerializationContext) {
            $event = new PostSerializeEvent($context, $object, $type);
        } else {
            $event = new PostDeserializeEvent($context, $object, $type);
        }

        $this->eventBus->dispatch($event);
    }
}
