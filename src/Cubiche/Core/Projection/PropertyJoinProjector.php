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

use Cubiche\Core\Equatable\EquatableInterface;

/**
 * Property Join Projector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyJoinProjector extends JoinProjector
{
    /**
     * @var string
     */
    protected $leftPropertyName;

    /**
     * @var string
     */
    protected $rightPropertyName;

    /**
     * @var bool
     */
    protected $exclusiveMode = false;

    /**
     * @param ProjectorInterface $left
     * @param ProjectorInterface $right
     * @param string             $leftPropertyName
     * @param string             $rightPropertyName
     */
    public function __construct(
        ProjectorInterface $left,
        ProjectorInterface $right,
        $leftPropertyName,
        $rightPropertyName
    ) {
        parent::__construct($left, $right);

        $this->leftPropertyName = $leftPropertyName;
        $this->rightPropertyName = $rightPropertyName;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkJoin(
        ProjectionWrapperInterface $leftProjection,
        ProjectionWrapperInterface $rightProjection
    ) {
        $this->checkProperty($leftProjection, $this->leftPropertyName());
        $this->checkProperty($rightProjection, $this->rightPropertyName());

        $leftValue = $leftProjection->get($this->leftPropertyName());
        $rightValue = $rightProjection->get($this->rightPropertyName());
        if ($leftValue instanceof EquatableInterface) {
            return $leftValue->equals($rightValue);
        }

        return $leftValue === $rightValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function doJoin(ProjectionBuilderInterface $leftBuilder, ProjectionBuilderInterface $rightBuilder)
    {
        return $leftBuilder->join($rightBuilder, $this->exclusiveMode() ? array($this->rightPropertyName()) : array());
    }

    /**
     * @return string
     */
    public function leftPropertyName()
    {
        return $this->leftPropertyName;
    }

    /**
     * @return string
     */
    public function rightPropertyName()
    {
        return $this->rightPropertyName;
    }

    /**
     * @return bool
     */
    public function exclusiveMode()
    {
        return $this->exclusiveMode;
    }

    public function exclusiveModeOn()
    {
        $this->setExclusiveMode(true);
    }

    public function exclusiveModeOff()
    {
        $this->setExclusiveMode(false);
    }

    /**
     * @param bool $exclusiveMode
     */
    public function setExclusiveMode($exclusiveMode)
    {
        $this->exclusiveMode = $exclusiveMode;
    }

    /**
     * @param ProjectionWrapperInterface $projection
     * @param string                     $property
     *
     * @throws \LogicException
     */
    private function checkProperty(ProjectionWrapperInterface $projection, $property)
    {
        if (!$projection->has($property)) {
            throw new \LogicException(
                \sprintf('There is not a property "%s" in %s class', $property, \get_class($projection->projection()))
            );
        }
    }
}
