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

/**
 * Class Projection Builder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ClassProjectionBuilder extends ProjectionBuilder
{
    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $reflection = new \ReflectionClass($class);
        $instance = $reflection->newInstanceWithoutConstructor();

        parent::__construct($instance);
    }

    /**
     * {@inheritdoc}
     */
    public function set($property, $value)
    {
        self::accessor()->setValue($this->projection(), $property, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($property)
    {
        $this->set($property, null);
    }

    /**
     * {@inheritdoc}
     */
    public function properties()
    {
        return self::projectionProperties($this->projection());
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyCopy()
    {
        return new self(\get_class($this->projection()));
    }
}
