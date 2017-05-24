<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\ArrayHashMapInterface;

/**
 * ClassMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassMetadata implements \Serializable, ClassMetadataInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var \ReflectionClass
     */
    protected $reflection;

    /**
     * @var ArrayHashMapInterface
     */
    protected $methodsMetadata;

    /**
     * @var ArrayHashMapInterface
     */
    protected $propertiesMetadata;

    /**
     * ClassMetadata constructor.
     *
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;

        $this->reflection = new \ReflectionClass($className);
        $this->methodsMetadata = new ArrayHashMap();
        $this->propertiesMetadata = new ArrayHashMap();
    }

    /**
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @return \ReflectionClass
     */
    public function reflection()
    {
        return $this->reflection;
    }

    /**
     * @return array
     */
    public function methodsMetadata()
    {
        return $this->methodsMetadata->toArray();
    }

    /**
     * @param MethodMetadata $metadata
     */
    public function addMethodMetadata(MethodMetadata $metadata)
    {
        $this->methodsMetadata->set($metadata->methodName(), $metadata);
    }

    /**
     * @param string $methodName
     *
     * @return MethodMetadata|null
     */
    public function methodMetadata($methodName)
    {
        return $this->methodsMetadata->get($methodName);
    }

    /**
     * @return array
     */
    public function propertiesMetadata()
    {
        return $this->propertiesMetadata->toArray();
    }

    /**
     * @param PropertyMetadata $metadata
     */
    public function addPropertyMetadata(PropertyMetadata $metadata)
    {
        $this->propertiesMetadata->set($metadata->propertyName(), $metadata);
    }

    /**
     * @param string $propertyName
     *
     * @return PropertyMetadata|null
     */
    public function propertyMetadata($propertyName)
    {
        return $this->propertiesMetadata->get($propertyName);
    }

    /**
     * @param ClassMetadataInterface $object
     *
     * @return ClassMetadataInterface
     */
    public function merge(ClassMetadataInterface $object)
    {
        $this->className = $object->className();
        $this->reflection = $object->reflection();

        foreach ($object->methodsMetadata() as $methodName => $metadata) {
            $this->addMethodMetadata($metadata);
        }

        foreach ($object->propertiesMetadata() as $propertyName => $metadata) {
            $this->addPropertyMetadata($metadata);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->className,
            $this->methodsMetadata->toArray(),
            $this->propertiesMetadata->toArray(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->className,
            $methodsMetadata,
            $propertiesMetadata) = unserialize($str);

        $this->reflection = new \ReflectionClass($this->className);
        $this->methodsMetadata = new ArrayHashMap($methodsMetadata);
        $this->propertiesMetadata = new ArrayHashMap($propertiesMetadata);
    }
}
