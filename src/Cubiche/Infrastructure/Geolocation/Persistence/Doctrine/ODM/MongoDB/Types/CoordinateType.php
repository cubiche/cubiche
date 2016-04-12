<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Geolocation\Persistence\Doctrine\ODM\MongoDB\Types;

use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Infrastructure\Model\Persistence\Doctrine\ODM\MongoDB\Types\ValueObjectType;

/**
 * Coordinate Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CoordinateType extends ValueObjectType
{
    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::convertToDatabaseValue()
     */
    public function convertToDatabaseValue($value)
    {
        if ($value === null) {
            return;
        }

        if (!$value instanceof Coordinate) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected %s instance, instance of %s given',
                $this->entityReflectionClass->name,
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
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::convertToPHPValue()
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
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::closureToMongo()
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
     *
     * @see \Doctrine\ODM\MongoDB\Types\Type::closureToPHP()
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
