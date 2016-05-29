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

use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Extended Projection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ExtendedProjection implements ProjectionInterface
{
    /**
     * @var ProjectionInterface
     */
    private $projection;

    /**
     * @var ProjectionInterface
     */
    private $joinLeftPropertyName = null;

    /**
     * @param ProjectionInterface $projection
     */
    public function __construct(ProjectionInterface $projection)
    {
        $this->projection = $projection;
    }

    /**
     * @param ProjectionInterface $projection
     *
     * @return \Cubiche\Core\Projection\ExtendedProjection
     */
    public function join(ProjectionInterface $projection)
    {
        $this->joinLeftPropertyName = null;

        return new self(new JoinProjection($this->projection, $projection));
    }

    /**
     * @param string $propertyName
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Core\Projection\ExtendedProjection
     */
    public function on($propertyName)
    {
        if (\get_class($this->projection) !== JoinProjection::class) {
            throw new \LogicException(
                \sprintf('The %s::on() method must be called after %s::join() method', self::class, self::class)
            );
        }

        $this->joinLeftPropertyName = $propertyName;

        return $this;
    }

    /**
     * @param unknown $propertyName
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Core\Projection\ExtendedProjection
     */
    public function eq($propertyName)
    {
        if (\get_class($this->projection) !== JoinProjection::class || $this->joinLeftPropertyName === null) {
            throw new \LogicException(
                \sprintf('The %s::eq() method must be called after %s::on() method', self::class, self::class)
            );
        }

        return new self(new PropertyJoinProjection(
            $this->projection->left(),
            $this->projection->right(),
            $this->joinLeftPropertyName,
            $propertyName
        ));
    }

    /**
     * @param SpecificationInterface $criteria
     *
     * @return \Cubiche\Core\Projection\ExtendedProjection
     */
    public function where(SpecificationInterface $criteria)
    {
        return new self(new WhereProjection($criteria, $this->projection));
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        return $this->projection->project($value);
    }
}
