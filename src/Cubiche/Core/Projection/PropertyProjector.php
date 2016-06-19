<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;

/**
 * Property Projector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyProjector implements ProjectorInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var ArrayHashMap
     */
    protected $properties;

    /**
     * @param string $class
     */
    public function __construct($class = null)
    {
        $this->class = $class;
        $this->properties = new ArrayHashMap();
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        $builder = $this->class === null ? new DefaultProjectionBuilder() : new ClassProjectionBuilder($this->class);
        /** @var \Cubiche\Core\Projection\Property $property */
        foreach ($this->properties as $property) {
            $builder->set($property->name(), $property->selector()->apply($value));
        }

        yield $builder;
    }

    /**
     * @param PropertyInterface $property
     */
    public function add(Property $property)
    {
        if ($this->properties->containsKey($property->name())) {
            throw new \LogicException('There already is a property with name '.$property->name());
        }

        $this->properties->set($property->name(), $property);
    }

    /**
     * @param Property[] $properties
     */
    public function addAll($properties)
    {
        foreach ($properties as $property) {
            $this->add($property);
        }
    }

    /**
     * @param string $propertyName
     */
    public function remove($propertyName)
    {
        $this->properties->removeAt($propertyName);
    }

    /**
     * @return Property[]
     */
    public function properties()
    {
        return $this->properties->toArray();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
}
