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
class JoinProjection implements ProjectionInterface
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
                if ($this->checkJoin($leftItem, $rightItem)) {
                    yield $this->doJoin($leftItem, $rightItem);
                }
            }
        }
    }

    /**
     * @param ProjectionItem $leftItem
     * @param ProjectionItem $rightItem
     *
     * @return bool
     */
    protected function checkJoin(ProjectionItem $leftItem, ProjectionItem $rightItem)
    {
        return true;
    }

    /**
     * @param ProjectionItem $leftItem
     * @param ProjectionItem $rightItem
     *
     * @return ProjectionItem
     */
    protected function doJoin(ProjectionItem $leftItem, ProjectionItem $rightItem)
    {
        return $leftItem->join($rightItem);
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
}
