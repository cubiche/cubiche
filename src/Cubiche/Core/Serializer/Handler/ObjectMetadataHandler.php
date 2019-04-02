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
use Cubiche\Core\Metadata\ClassMetadataFactoryInterface;
use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Exception\SerializationException;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;

/**
 * ObjectMetadataHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ObjectMetadataHandler implements HandlerInterface
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * ObjectMetadataHandler constructor.
     *
     * @param ClassMetadataFactoryInterface $metadataFactory
     */
    public function __construct(ClassMetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

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
        // get the class metadata
        $classMetadata = $this->getClassMetadata($type['name']);
        if ($classMetadata === null) {
            throw SerializationException::invalidMapping($type['name']);
        }

        $visitor->startVisitingObject($classMetadata, $object, $type, $context);
        foreach ($classMetadata->propertiesMetadata() as $propertyMetadata) {
            $visitor->visitProperty($propertyMetadata, $object, $context);
        }

        return $visitor->endVisitingObject($classMetadata, $object, $type, $context);
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
        // get the class metadata
        $classMetadata = $this->getClassMetadata($type['name']);
        if ($classMetadata === null) {
            throw SerializationException::invalidMapping($type['name']);
        }

        $object = $classMetadata->reflection()->newInstanceWithoutConstructor();

        $visitor->startVisitingObject($classMetadata, $object, $type, $context);
        foreach ($classMetadata->propertiesMetadata() as $propertyMetadata) {
            $visitor->visitProperty($propertyMetadata, $data, $context);
        }

        return $visitor->endVisitingObject($classMetadata, $data, $type, $context);
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
     * {@inheritdoc}
     */
    public function supports($typeName, ContextInterface $context)
    {
        return $this->getClassMetadata($typeName) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function order()
    {
        return 450;
    }
}
