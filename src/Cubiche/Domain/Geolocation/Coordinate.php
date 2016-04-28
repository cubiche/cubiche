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

use Cubiche\Domain\Model\ValueObjectInterface;
use Cubiche\Domain\System\Real;

/**
 * Coordinate Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Coordinate implements ValueObjectInterface
{
    const EARTH_RADIUS = 6378136.0; //m

    /**
     * @var Latitude
     */
    protected $latitude;

    /**
     * @var Longitude
     */
    protected $longitude;

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return Coordinate
     */
    public static function fromLatLng($latitude, $longitude)
    {
        return new static(new Latitude($latitude), new Longitude($longitude));
    }

    /**
     * @param Latitude  $latitude
     * @param Longitude $longitude
     */
    public function __construct(Latitude $latitude, Longitude $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return Latitude
     */
    public function latitude()
    {
        return $this->latitude;
    }

    /**
     * @return Longitude
     */
    public function longitude()
    {
        return $this->longitude;
    }

    /**
     * @param Coordinate   $coordinate
     * @param DistanceUnit $unit
     */
    public function distance(Coordinate $coordinate, DistanceUnit $unit = null)
    {
        $latA = \deg2rad($this->latitude());
        $lngA = \deg2rad($this->longitude());
        $latB = \deg2rad($coordinate->latitude());
        $lngB = \deg2rad($coordinate->longitude());
        $degrees = \acos(\sin($latA) * \sin($latB) + \cos($latA) * \cos($latB) * \cos($lngB - $lngA));
        $unit = $unit === null ? DistanceUnit::METER() : $unit;

        return (new Distance(Real::fromNative($degrees * self::EARTH_RADIUS), DistanceUnit::METER()))->in($unit);
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return $other instanceof self &&
            $this->latitude()->equals($other->latitude()) &&
            $this->longitude()->equals($other->longitude());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return \sprintf('[%F, %F]', $this->latitude()->toNative(), $this->longitude()->toNative());
    }
}
