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
 * Default Projection Builder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DefaultProjectionBuilder extends ProjectionBuilder
{
    /**
     * @param object $projection
     *
     * @return \Cubiche\Core\Projection\DefaultProjectionBuilder
     */
    public static function fromObject($projection)
    {
        $builder = new self();
        foreach (self::projectionProperties($projection) as $property => $value) {
            $builder->set($property, $value);
        }

        return $builder;
    }

    public function __construct()
    {
        parent::__construct(new \stdClass());
    }

    /**
     * {@inheritdoc}
     */
    public function has($property)
    {
        return isset($this->projection->{$property});
    }

    /**
     * {@inheritdoc}
     */
    public function get($property)
    {
        return $this->has($property) ? $this->projection->{$property} : null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($property, $value)
    {
        $this->projection->{$property} = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($property)
    {
        unset($this->projection->{$property});
    }

    /**
     * {@inheritdoc}
     */
    public function properties()
    {
        return self::stdClassProjectionProperties($this->projection);
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyCopy()
    {
        return new self();
    }

    /**
     * @param object $projection
     *
     * @return Generator
     */
    protected static function projectionProperties($projection)
    {
        if ($projection instanceof \stdClass) {
            return self::stdClassProjectionProperties($projection);
        } else {
            return parent::projectionProperties($projection);
        }
    }

    /**
     * @param object $projection
     *
     * @return Generator
     */
    private static function stdClassProjectionProperties($projection)
    {
        foreach (\get_object_vars($projection) as $property => $value) {
            yield $property => $value;
        }
    }
}
