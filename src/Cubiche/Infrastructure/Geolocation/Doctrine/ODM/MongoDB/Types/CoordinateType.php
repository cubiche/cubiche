<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Geolocation\Doctrine\ODM\MongoDB\Types;

use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types\ValueObjectType;

/**
 * Coordinate Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CoordinateType extends ValueObjectType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        if ($value === null) {
            return;
        }

        if (!$value instanceof Coordinate) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected %s instance, instance of %s given',
                Coordinate::class,
                \is_object($value) ? \gettype($value) : \get_class($value)
            ));
        }

        return array(
            'type' => 'Point',
            'coordinates' => array(
                $value->longitude()->toNative(),
                $value->latitude()->toNative(),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        if ($value === null) {
            return;
        }

        return Coordinate::fromLatLng($value['coordinates'][1], $value['coordinates'][0]);
    }

    /**
     * {@inheritdoc}
     */
    public function closureToMongo()
    {
        return
        '$return = $value === null ? null : array(
            "type" => "Point",
            "coordinates" => array(
                $value->longitude()->toNative(),
                $value->latitude()->toNative()
        ));';
    }

    /**
     * {@inheritdoc}
     */
    public function closureToPHP()
    {
        return
        '$return = $value === null ? null :'.
            Coordinate::class.'::fromLatLng(
                $value["coordinates"][1],
                $value["coordinates"][0]
        );';
    }
}
