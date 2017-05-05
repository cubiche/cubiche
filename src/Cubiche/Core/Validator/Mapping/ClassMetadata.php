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

use Cubiche\Core\Validator\Assert;
use Metadata\ClassMetadata as BaseClassMetadata;

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
     * @return string
     */
    public function getClassName()
    {
        return $this->name;
    }

    /**
     * @param string $property
     *
     * @return PropertyMetadata|null
     */
    protected function getPropertyMetadata($property)
    {
        return isset($this->propertyMetadata[$property]) ? $this->propertyMetadata[$property] : null;
    }

    /**
     * @param string $property
     * @param Assert $constraint
     * @param string $group
     */
    public function addPropertyConstraint($property, Assert $constraint, $group = null)
    {
        $propertyMetadata = $this->getPropertyMetadata($property);
        if ($propertyMetadata === null) {
            $propertyMetadata = new PropertyMetadata($this->getClassName(), $property);
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
     * @return PropertyMetadata[]
     */
    public function getPropertiesMetadata()
    {
        return $this->propertyMetadata;
    }

    /**
     * @param string $method
     *
     * @return MethodMetadata|null
     */
    protected function getMethodMetadata($method)
    {
        return isset($this->methodMetadata[$method]) ? $this->methodMetadata[$method] : null;
    }

    /**
     * @param string $method
     * @param Assert $constraint
     * @param string $group
     */
    public function addMethodConstraint($method, Assert $constraint, $group = null)
    {
        $methodMetadata = $this->getMethodMetadata($method);
        if ($methodMetadata === null) {
            $methodMetadata = new MethodMetadata($this->getClassName(), $method);
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
     * @return MethodMetadata[]
     */
    public function getMethodsMetadata()
    {
        return $this->methodMetadata;
    }

    /**
     * Merges the constraints of the given metadata into this object.
     *
     * @param ClassMetadata $source
     */
    public function mergeConstraints(ClassMetadata $source)
    {
        foreach ($source->getPropertiesMetadata() as $property => $propertyMetadata) {
            foreach ($propertyMetadata->getConstraints() as $group => $constraints) {
                $this->addPropertyConstraints($property, $constraints, $group);
            }
        }

        foreach ($source->getMethodsMetadata() as $method => $methodMetadata) {
            foreach ($methodMetadata->getConstraints() as $group => $constraints) {
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
            $this->name,
            $this->methodMetadata,
            $this->propertyMetadata,
            $this->fileResources,
            $this->createdAt,
            $this->defaultGroup,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->name,
            $this->methodMetadata,
            $this->propertyMetadata,
            $this->fileResources,
            $this->createdAt,
            $this->defaultGroup) = unserialize($str);

        $this->reflection = new \ReflectionClass($this->name);
    }
}
