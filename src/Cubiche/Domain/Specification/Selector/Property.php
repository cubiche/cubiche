<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Selector;

use Cubiche\Domain\Specification\SpecificationVisitorInterface;
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
     * @return \Cubiche\Domain\Specification\Selector\PropertyAccessor
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
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::accept()
     */
    public function accept(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitProperty($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\Selector::apply()
     */
    public function apply($value)
    {
        if (!is_object($value)) {
            throw new \RuntimeException('Trying to get property of non-object');
        }

        if (!property_exists($value, $this->name)) {
            throw new \RuntimeException(\sprintf('Undefined property %s::%s', \get_class($value), $this->name));
        }

        return self::accessor()->getValue($value, $this->name);
    }
}
