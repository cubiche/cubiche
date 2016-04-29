<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Geolocation;

/**
 * Geolocalizable Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait GeolocalizableTrait
{
    /**
     * @var Coordinate
     */
    protected $coordinate;

    /**
     * {@inheritdoc}
     */
    public function coordinate()
    {
        return $this->coordinate;
    }

    /**
     * {@inheritdoc}
     */
    public function setCoordinate(Coordinate $coordinate)
    {
        $this->coordinate = $coordinate;
    }

    /**
     * @return bool
     */
    public function hasCoordinate()
    {
        return $this->coordinate !== null;
    }
}
