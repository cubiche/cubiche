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

/**
 * Projection Wrapper Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ProjectionWrapper implements ProjectionWrapperInterface
{
    /**
     * @var object
     */
    protected $projection;

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
     * @param object $projection
     */
    public function __construct($projection)
    {
        if (\is_array($projection)) {
            $projection = (object) $projection;
        }
        if (!\is_object($projection)) {
            throw new \InvalidArgumentException('The $projection parameter must be an object');
        }

        $this->projection = $projection;
    }

    /**
     * {@inheritdoc}
     */
    public function has($property)
    {
        return self::accessor()->isWritable($this->projection(), $property) && $this->get($property) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function get($property)
    {
        return self::accessor()->getValue($this->projection(), $property);
    }

    /**
     * {@inheritdoc}
     */
    public function projection()
    {
        return $this->projection;
    }
}
