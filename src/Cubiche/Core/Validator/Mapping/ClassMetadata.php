<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Mapping;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Metadata\ClassMetadata as BaseClassMetadata;

/**
 * ClassMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassMetadata extends BaseClassMetadata
{
    /**
     * @var string
     */
    public $defaultGroup;

    /**
     * @param string    $property
     * @param Assertion $constraint
     * @param string    $group
     */
    public function addPropertyConstraint($property, Assertion $constraint, $group = null)
    {
        $propertyMetadata = $this->propertyMetadata($property);
        if ($propertyMetadata === null) {
            $propertyMetadata = new PropertyMetadata($this->className(), $property);
            $propertyMetadata->defaultGroup = $this->defaultGroup;
        }

        $propertyMetadata->addConstraint($constraint, $group);

        $this->addPropertyMetadata($propertyMetadata);
    }

    /**
     * @param string $property
     * @param array  $constraints
     * @param string $group
     */
    public function addPropertyConstraints($property, array $constraints, $group = null)
    {
        foreach ($constraints as $constraint) {
            $this->addPropertyConstraint($property, $constraint, $group);
        }
    }

    /**
     * @param string    $method
     * @param Assertion $constraint
     * @param string    $group
     */
    public function addMethodConstraint($method, Assertion $constraint, $group = null)
    {
        $methodMetadata = $this->methodMetadata($method);
        if ($methodMetadata === null) {
            $methodMetadata = new MethodMetadata($this->className(), $method);
            $methodMetadata->defaultGroup = $this->defaultGroup;
        }

        $methodMetadata->addConstraint($constraint, $group);

        $this->addMethodMetadata($methodMetadata);
    }

    /**
     * @param string $method
     * @param array  $constraints
     * @param string $group
     */
    public function addMethodConstraints($method, array $constraints, $group = null)
    {
        foreach ($constraints as $constraint) {
            $this->addMethodConstraint($method, $constraint, $group);
        }
    }

    /**
     * Merges the constraints of the given metadata into this object.
     *
     * @param ClassMetadata $source
     */
    public function mergeConstraints(ClassMetadata $source)
    {
        /** @var PropertyMetadata $propertyMetadata */
        foreach ($source->propertiesMetadata() as $property => $propertyMetadata) {
            foreach ($propertyMetadata->constraints() as $group => $constraints) {
                $this->addPropertyConstraints($property, $constraints, $group);
            }
        }

        /** @var MethodMetadata $methodMetadata */
        foreach ($source->methodsMetadata() as $method => $methodMetadata) {
            foreach ($methodMetadata->constraints() as $group => $constraints) {
                $this->addMethodConstraints($method, $constraints, $group);
            }
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
            $this->metadata->toArray(),
            $this->defaultGroup,
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
            $propertiesMetadata,
            $metadata,
            $this->defaultGroup
        ) = unserialize($str);

        $this->reflection = new \ReflectionClass($this->className);
        $this->methodsMetadata = new ArrayHashMap($methodsMetadata);
        $this->propertiesMetadata = new ArrayHashMap($propertiesMetadata);
        $this->metadata = new ArrayHashMap($metadata);
    }
}
