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
 * Join Projection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class JoinProjection extends Projection
{
    /**
     * @var ProjectionInterface
     */
    protected $left;

    /**
     * @var ProjectionInterface
     */
    protected $right;

    /**
     * @param ProjectionInterface $left
     * @param ProjectionInterface $right
     */
    public function __construct(ProjectionInterface $left, ProjectionInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        /** @var \Cubiche\Core\Projection\ProjectionItem $leftItem */
        foreach ($this->left()->project($value) as $leftItem) {
            /** @var \Cubiche\Core\Projection\ProjectionItem $rightItem */
            foreach ($this->right()->project($value) as $rightItem) {
                yield $leftItem->join($rightItem);
            }
        }
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectionInterface
     */
    public function left()
    {
        return $this->left;
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectionInterface
     */
    public function right()
    {
        return $this->right;
    }

    /**
     * {@inheritdoc}
     */
    public function properties()
    {
        $projection = new SimpleProjection();
        foreach ($this->left()->properties() as $property) {
            $projection->add($property);
        }
        foreach ($this->right()->properties() as $property) {
            $projection->add($property);
        }

        return $projection->properties();
    }
}
