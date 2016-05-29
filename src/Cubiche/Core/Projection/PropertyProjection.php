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

use Cubiche\Core\Collections\ArrayCollection;

/**
 * Property Projection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyProjection implements ProjectionInterface
{
    /**
     * @var ArrayCollection
     */
    protected $properties;

    /**
     * @param Property[] $properties
     */
    public function __construct($properties = array())
    {
        $this->properties = new ArrayCollection();
        $this->addAll($properties);
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        $item = new ProjectionItem();
        /** @var \Cubiche\Core\Projection\Property $property */
        foreach ($this->properties as $property) {
            $item->set($property->name(), $property->selector()->apply($value));
        }

        yield $item;
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
}
