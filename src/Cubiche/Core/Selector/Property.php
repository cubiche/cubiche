<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Property Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Property extends Field
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
     * {@inheritdoc}
     */
    public function apply($value)
    {
        if (!\is_object($value)) {
            throw new \RuntimeException('Trying to get property of non-object');
        }

        if (!\property_exists($value, $this->name)) {
            throw new \RuntimeException(\sprintf('Undefined property %s::%s', \get_class($value), $this->name));
        }

        return self::accessor()->getValue($value, $this->name);
    }
}
