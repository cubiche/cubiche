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

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Projection Item Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ProjectionItem
{
    /**
     * @var PropertyAccessorInterface
     */
    private static $accessor = null;

    /**
     * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    protected static function accessor()
    {
        if (self::$accessor === null) {
            self::$accessor = PropertyAccess::createPropertyAccessor();
        }

        return self::$accessor;
    }

    /**
     * @param string $property
     * @param mixed  $value
     */
    public function set($property, $value)
    {
        $this->{$property} = $value;
    }

    /**
     * @param ProjectionItem $item
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Core\Projection\ProjectionItem
     */
    public function join(ProjectionItem $item)
    {
        $joinedItem = clone $this;
        foreach ($item as $property => $value) {
            if (isset($joinedItem->{$property})) {
                throw new \LogicException('There already is a property with name '.$property);
            }
            $joinedItem->set($property, $value);
        }

        return $joinedItem;
    }

    /**
     * @param string $class
     *
     * @return mixed
     */
    public function to($class)
    {
        $object = new $class();
        foreach ($object as $property => $value) {
            self::accessor()->setValue($object, $property, $value);
        }

        return $object;
    }
}
