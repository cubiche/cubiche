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
 * Abstract Projection Builder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ProjectionBuilder extends ProjectionWrapper implements ProjectionBuilderInterface
{
    /**
     * @param ProjectionWrapperInterface $projection
     *
     * @return \Cubiche\Core\Projection\ProjectionBuilder
     */
    public static function fromWrapper(ProjectionWrapperInterface $projection)
    {
        if ($projection instanceof self) {
            return $projection;
        }

        return DefaultProjectionBuilder::fromObject($projection->projection());
    }

    /**
     * @param object $projection
     *
     * @return Generator
     */
    protected static function projectionProperties($projection)
    {
        $reflection = new \ReflectionClass(\get_class($projection));
        /** @var \ReflectionProperty $property */
        foreach ($reflection->getProperties() as $property) {
            yield $property->getName() => $property->getValue($projection);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function join(ProjectionWrapperInterface $projection, $exclude = array())
    {
        return self::fromWrapper($projection)->copyTo($this->copy(), $exclude);
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectionBuilderInterface
     */
    protected function copy()
    {
        return $this->copyTo($this->emptyCopy());
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectionBuilderInterface
     */
    abstract protected function emptyCopy();

    /**
     * @param ProjectionBuilderInterface $builder
     * @param string[]                   $exclude
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Core\Projection\ProjectionBuilderInterface
     */
    protected function copyTo(ProjectionBuilderInterface $builder, $exclude = array())
    {
        foreach ($this->properties() as $property => $value) {
            if (\in_array($property, $exclude)) {
                continue;
            }
            if ($builder->has($property)) {
                throw new \LogicException('There already is a property with name '.$property);
            }
            $builder->set($property, $value);
        }

        return $builder;
    }
}
