<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Handler;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\ClassMetadataInterface;
use Cubiche\Core\Metadata\PropertyMetadata;
use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Context\SerializationContext;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;
use \ReflectionMethod;

/**
 * ObjectHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ObjectHandler extends ObjectMetadataHandler
{
    /**
     * @param SerializationVisitor  $visitor
     * @param object                $object
     * @param array                 $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function serialize(SerializationVisitor $visitor, $object, array $type, ContextInterface $context)
    {
        // ensure that a class metadata exists
        $this->ensureClassMetadata($type['name'], $object, $context);

        return parent::serialize($visitor, $object, $type, $context);
    }

    /**
     * @param DeserializationVisitor $visitor
     * @param mixed                  $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, $data, array $type, ContextInterface $context)
    {
        // ensure that a class metadata exists
        $this->ensureClassMetadata($type['name'], $data, $context);

        return parent::deserialize($visitor, $data, $type, $context);
    }

    /**
     * @param string $className
     *
     * @return ClassMetadataInterface
     */
    protected function createClassMetadata($className)
    {
        return new ClassMetadata($className);
    }

    /**
     * Returns the metadata for a class.
     *
     * @param string           $className
     * @param object|array     $object
     * @param ContextInterface $context
     *
     * @return ClassMetadata
     */
    protected function ensureClassMetadata($className, $object, ContextInterface $context)
    {
        $classMetadata = $this->getClassMetadata($className);
        if ($classMetadata === null) {
            // create the class metadata on the fly
            $classMetadata = $this->createClassMetadata($className);
            $this->extractPropertiesMetadata($classMetadata, $object, $context);

            // persist it
            $this->metadataFactory->setMetadataFor($className, $classMetadata);
        }
    }

    /**
     * @param ClassMetadata    $classMetadata
     * @param object|array     $object
     * @param ContextInterface $context
     */
    protected function extractPropertiesMetadata(ClassMetadata $classMetadata, $object, ContextInterface $context)
    {
        // extract metadata from properties
        foreach ($classMetadata->reflection()->getProperties() as $reflProperty) {
            if ($reflProperty->isStatic()) {
                continue;
            }

            $propertyName = $reflProperty->name;

            $propertyMetadata = new PropertyMetadata($classMetadata->className(), $propertyName);
            $propertyMetadata->addMetadata('name', $propertyName);

            // extract the property type
            if ($context instanceof SerializationContext) {
                // from the property value
                $propertyValue = $propertyMetadata->getValue($object);
                $propertyType = is_object($propertyValue) ? get_class($propertyValue) : gettype($propertyValue);
            } else {
                // from class getter method definition
                $propertyType = $this->getPropertyType($classMetadata, $propertyName);
            }

            $propertyMetadata->addMetadata('type', $propertyType);
            $classMetadata->addPropertyMetadata($propertyMetadata);
        }
    }

    /**
     * Get the property type base on the getter method return type.
     *
     * @param ClassMetadata $classMetadata
     * @param string        $propertyName
     *
     * @return null|string
     */
    private function getPropertyType(ClassMetadata $classMetadata, string $propertyName): ?string
    {
        $reflMethod = $this->findGetMethod($classMetadata, $propertyName);
        if ($reflMethod !== null && $this->canBeCallable($reflMethod)) {
            return $reflMethod->getReturnType()->__toString();
        }

        return null;
    }

    /**
     * Checks if the given class has any get{Property} method.
     *
     * @param ClassMetadata $classMetadata
     * @param string        $propertyName
     *
     * @return null|ReflectionMethod
     */
    private function findGetMethod(ClassMetadata $classMetadata, string $propertyName): ?ReflectionMethod
    {
        $sufix = ucfirst(strtolower($propertyName));
        if ($classMetadata->reflection()->hasMethod('get'.$sufix)) {
            return $classMetadata->reflection()->getMethod('get'.$sufix);
        }

        if ($classMetadata->reflection()->hasMethod('is'.$sufix)) {
            return $classMetadata->reflection()->getMethod('is'.$sufix);
        }

        if ($classMetadata->reflection()->hasMethod('has'.$sufix)) {
            return $classMetadata->reflection()->getMethod('has'.$sufix);
        }

        if ($classMetadata->reflection()->hasMethod($propertyName)) {
            return $classMetadata->reflection()->getMethod($propertyName);
        }

        return null;
    }

    /**
     * Checks if a method can be called without parameters.
     *
     * @param ReflectionMethod $method
     *
     * @return bool
     */
    private function canBeCallable(ReflectionMethod $method): bool
    {
        return !$method->isStatic() &&  0 === $method->getNumberOfRequiredParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($typeName, ContextInterface $context)
    {
        try {
            new \ReflectionClass($typeName);

            return true;
        } catch (\ReflectionException $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function order()
    {
        return 500;
    }
}
