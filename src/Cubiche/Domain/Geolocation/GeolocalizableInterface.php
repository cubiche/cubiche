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
 * Geolocalizable Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface GeolocalizableInterface
{
    /**
     * @return Coordinate
     */
    public function coordinate();

    /**
     * @param Coordinate $coordinate
     */
    public function setCoordinate(Coordinate $coordinate);
}
