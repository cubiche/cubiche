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

use Cubiche\Domain\System\Enum;

/**
 * Distance Unit Enum.
 *
 * @method DistanceUnit METER()
 * @method DistanceUnit FOOT()
 * @method DistanceUnit KILOMETER()
 * @method DistanceUnit MILE()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class DistanceUnit extends Enum
{
    const FOOT = 'ft';
    const METER = 'm';
    const KILOMETER = 'km';
    const MILE = 'mi';

    /**
     * @param DistanceUnit $unit
     *
     * @return float
     */
    public function conversionRate(DistanceUnit $unit)
    {
        if ($this->equals($unit)) {
            return 1;
        }

        return $this->conversionRateToMeters() / $unit->conversionRateToMeters();
    }

    /**
     * @return float
     */
    private function conversionRateToMeters()
    {
        switch ($this->value) {
            case self::METER:
                return 1;
            case self::KILOMETER:
                return 1000;
            case self::MILE:
                return 1609.344;
            case self::FOOT:
                return 0.3048;
            default:
                return 1;
        }
    }
}
