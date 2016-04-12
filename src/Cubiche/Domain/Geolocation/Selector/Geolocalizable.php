<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Geolocation\Selector;

use Cubiche\Core\Selector\This;
use Cubiche\Core\Selector\Property;
use Cubiche\Domain\Geolocation\Specification\Constraint\Near;
use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Domain\Geolocation\Distance;

/**
 * Geolocalizable Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Geolocalizable extends This
{
    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function coordinate()
    {
        return $this->select(new Property('coordinate'));
    }

    /**
     * @param Coordinate $coordinate
     * @param Distance   $radius
     *
     * @return \Cubiche\Domain\Geolocation\Specification\Constraint\Near
     */
    public function near(Coordinate $coordinate, Distance $radius = null)
    {
        return new Near($this, $coordinate, $radius);
    }
}
