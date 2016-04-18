<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Geolocation\Specification;

use Cubiche\Domain\Geolocation\Selector\Geolocalizable;

/**
 * Geolocalizable Criteria Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait GeolocalizableCriteriaTrait
{
    /**
     * @var Geolocalizable
     */
    protected static $geolocalizable = null;

    /**
     * @return \Cubiche\Domain\Geolocation\Selector\Geolocalizable
     */
    public static function asGeolocalizable()
    {
        if (self::$geolocalizable === null) {
            self::$geolocalizable = new Geolocalizable();
        }

        return self::$geolocalizable;
    }
}
