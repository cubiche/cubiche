<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Handler;

use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;
use Cubiche\Domain\Geolocation\Coordinate;

/**
 * CoordinateHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CoordinateHandler implements HandlerInterface
{
    /**
     * @param SerializationVisitor $visitor
     * @param Coordinate           $coordinate
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(SerializationVisitor $visitor, $coordinate, array $type, ContextInterface $context)
    {
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
    public function deserialize(DeserializationVisitor $visitor, $data, array $type, ContextInterface $context)
    {
        $newType = array('name' => 'float', 'params' => array());

        $latitude = $visitor->visitDouble($data['lat'], $newType, $context);
        $longitude = $visitor->visitDouble($data['lng'], $newType, $context);

        return Coordinate::fromLatLng($latitude, $longitude);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($typeName, ContextInterface $context)
    {
        return \is_a($typeName, Coordinate::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function order()
    {
        return 150;
    }
}
