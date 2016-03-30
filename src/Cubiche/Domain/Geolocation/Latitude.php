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
 * Latitude Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Latitude extends Real
{
    /**
     * @param float $value
     */
    public function __construct($value)
    {
        parent::__construct($this->normalize($value));
    }

    /**
     * @param float $latitude
     *
     * @return float
     */
    private function normalize($latitude)
    {
        return (double) \max(-90, \min(90, $latitude));
    }
}
