<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Geolocation\Specification\Constraint;

use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Specification\Specification;
use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Domain\Geolocation\Distance;
use Cubiche\Domain\Geolocation\DistanceUnit;
use Cubiche\Domain\Geolocation\GeolocalizableInterface;
use Cubiche\Domain\System\Real;

/**
 * Near Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Near extends Specification
{
    const DEFAULT_RADIUS = 50000; //m

    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @var Coordinate
     */
    protected $coordinate;

    /**
     * @var Distance
     */
    protected $radius;

    /**
     * @param SelectorInterface $selector
     * @param Coordinate        $coordinate
     * @param Distance          $radius
     */
    public function __construct(SelectorInterface $selector, Coordinate $coordinate, Distance $radius = null)
    {
        $this->selector = $selector;
        $this->coordinate = $coordinate;
        $this->radius = $radius === null ?
            new Distance(Real::fromNative(self::DEFAULT_RADIUS), DistanceUnit::METER()) :
            $radius;
    }

    /**
     * @return \Cubiche\Domain\Specification\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @return \Cubiche\Domain\Geolocation\Coordinate
     */
    public function coordinate()
    {
        return $this->coordinate;
    }

    /**
     * @return \Cubiche\Domain\Geolocation\Distance
     */
    public function radius()
    {
        return $this->radius;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        $geolocalizable = $this->selector->apply($value);
        if (!$geolocalizable instanceof GeolocalizableInterface) {
            throw new \LogicException(\sprintf(
                '%s not implement %s, the near operator is defined only to %s instances',
                \is_object($geolocalizable) ? \gettype($geolocalizable) : \get_class($geolocalizable),
                GeolocalizableInterface::class,
                GeolocalizableInterface::class
            ));
        }

        return $geolocalizable->coordinate() !== null &&
            $geolocalizable->coordinate()->distance($this->coordinate())->compareTo($this->radius()) <= 0;
    }
}
