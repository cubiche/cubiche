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
 * Join Projector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class JoinProjector implements ProjectorInterface
{
    /**
     * @var ProjectorInterface
     */
    protected $left;

    /**
     * @var ProjectorInterface
     */
    protected $right;

    /**
     * @param ProjectorInterface $left
     * @param ProjectorInterface $right
     */
    public function __construct(ProjectorInterface $left, ProjectorInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        /** @var \Cubiche\Core\Projection\ProjectionWrapperInterface $leftProjection */
        foreach ($this->left()->project($value) as $leftProjection) {
            /** @var \Cubiche\Core\Projection\ProjectionWrapperInterface $rightProjection */
            foreach ($this->right()->project($value) as $rightProjection) {
                if ($this->checkJoin($leftProjection, $rightProjection)) {
                    yield $this->doJoin(
                        ProjectionBuilder::fromWrapper($leftProjection),
                        ProjectionBuilder::fromWrapper($rightProjection)
                    );
                }
            }
        }
    }

    /**
     * @param ProjectionWrapperInterface $leftProjection
     * @param ProjectionWrapperInterface $rightProjection
     *
     * @return bool
     */
    protected function checkJoin(
        ProjectionWrapperInterface $leftProjection,
        ProjectionWrapperInterface $rightProjection
    ) {
        return true;
    }

    /**
     * @param ProjectionBuilderInterface $leftBuilder
     * @param ProjectionBuilderInterface $rightBuilder
     *
     * @return ProjectionBuilderInterface
     */
    protected function doJoin(ProjectionBuilderInterface $leftBuilder, ProjectionBuilderInterface $rightBuilder)
    {
        return $leftBuilder->join($rightBuilder);
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectorInterface
     */
    public function left()
    {
        return $this->left;
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectorInterface
     */
    public function right()
    {
        return $this->right;
    }
}
