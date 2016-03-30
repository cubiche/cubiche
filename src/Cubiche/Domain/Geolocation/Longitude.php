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

use Cubiche\Domain\System\Real;

/**
 * Longitude Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Longitude extends Real
{
    /**
     * @param float $value
     */
    public function __construct($value)
    {
        parent::__construct($this->normalize($value));
    }

    /**
     * @param float $longitude
     *
     * @return float
     */
    private function normalize($longitude)
    {
        if ($longitude % 360 === 180) {
            return 180.0;
        }

        $mod = \fmod($longitude, 360);

        return (double) $mod < -180 ? $mod + 360 : ($mod > 180 ? $mod - 360 : $mod);
    }
}
