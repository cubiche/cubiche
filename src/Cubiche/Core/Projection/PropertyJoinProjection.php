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
 * Property Join Projection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyJoinProjection extends JoinProjection
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
     * @param ProjectionInterface $left
     * @param ProjectionInterface $right
     */
    public function __construct(
        ProjectionInterface $left,
        ProjectionInterface $right,
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
    protected function checkJoin(ProjectionItem $leftItem, ProjectionItem $rightItem)
    {
        $leftValue = $leftItem->get($this->leftPropertyName);
        $rightValue = $rightItem->get($this->rightPropertyName);
        if ($leftValue instanceof EquatableInterface) {
            return $leftValue->equals($rightValue);
        }

        return $leftValue === $rightValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function doJoin(ProjectionItem $leftItem, ProjectionItem $rightItem)
    {
        $rightItem->remove($this->rightPropertyName);

        return parent::doJoin($leftItem, $rightItem);
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
}
