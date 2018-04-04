<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder\Handler;

use Cubiche\Core\Encoder\Context\ContextInterface;
use Cubiche\Core\Encoder\Visitor\DeserializationVisitor;
use Cubiche\Core\Encoder\Visitor\SerializationVisitor;
use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Domain\Geolocation\Latitude;
use Cubiche\Domain\Geolocation\Longitude;

/**
 * CoordinateHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CoordinateHandler implements HandlerSubscriberInterface
{
    /**
     * @param SerializationVisitor $visitor
     * @param Coordinate           $coordinate
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(
        SerializationVisitor $visitor,
        Coordinate $coordinate,
        array $type,
        ContextInterface $context
    ) {
        $newType = array('name' => 'float', 'params' => array());

        return array(
            'lat' => $visitor->visitDouble($coordinate->latitude()->toNative(), $newType, $context),
            'lng' => $visitor->visitDouble($coordinate->longitude()->toNative(), $newType, $context),
        );
    }

    /**
     * @param DeserializationVisitor $visitor
     * @param array                  $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, array $data, array $type, ContextInterface $context)
    {
        $newType = array('name' => 'float', 'params' => array());

        $latitude = $visitor->visitDouble($data['lat'], $newType, $context);
        $longitude = $visitor->visitDouble($data['lng'], $newType, $context);

        return Coordinate::fromLatLng($latitude, $longitude);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedHandlers()
    {
        return array(
            'serializers' => array(
                Coordinate::class => 'serialize',
            ),
            'deserializers' => array(
                Coordinate::class => 'deserialize',
            ),
        );
    }
}
