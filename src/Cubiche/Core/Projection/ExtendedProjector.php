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
 * Extended Projector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ExtendedProjector implements ProjectorInterface
{
    /**
     * @var ProjectorInterface
     */
    private $projector;

    /**
     * @var string
     */
    private $joinLeftPropertyName = null;

    /**
     * @param ProjectionInterface $projection
     */
    public function __construct(ProjectorInterface $projector)
    {
        $this->projector = $projector;
    }

    /**
     * @param ProjectorInterface $projector
     *
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public function join(ProjectorInterface $projector)
    {
        $this->joinLeftPropertyName = null;

        return new self(new JoinProjector($this->projector, $projector));
    }

    /**
     * @param string $propertyName
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public function on($propertyName)
    {
        if (\get_class($this->projector) !== JoinProjector::class) {
            throw new \LogicException(
                \sprintf('The %s::on() method must be called after %s::join() method', self::class, self::class)
            );
        }

        $this->joinLeftPropertyName = $propertyName;

        return $this;
    }

    /**
     * @param string $propertyName
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public function eq($propertyName)
    {
        if (\get_class($this->projector) !== JoinProjector::class || $this->joinLeftPropertyName === null) {
            throw new \LogicException(
                \sprintf('The %s::eq() method must be called after %s::on() method', self::class, self::class)
            );
        }

        return new self(new PropertyJoinProjector(
            $this->projector->left(),
            $this->projector->right(),
            $this->joinLeftPropertyName,
            $propertyName
        ));
    }

    /**
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public function exclusiveModeOn()
    {
        return $this->setExclusiveMode(true);
    }

    /**
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public function exclusiveModeOff()
    {
        return $this->setExclusiveMode(false);
    }

    /**
     * @param bool $exclusiveMode
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public function setExclusiveMode($exclusiveMode)
    {
        if (\get_class($this->projector) !== PropertyJoinProjector::class) {
            throw new \LogicException(
                \sprintf(
                    'The %s::setExclusiveMode() method must be called after %s::eq() method',
                    self::class,
                    self::class
                )
            );
        }

        $this->projector->setExclusiveMode($exclusiveMode);

        return $this;
    }

    /**
     * @param SpecificationInterface $criteria
     *
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public function where(SpecificationInterface $criteria)
    {
        return new self(new WhereProjector($criteria, $this->projector));
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        return $this->projector->project($value);
    }
}
