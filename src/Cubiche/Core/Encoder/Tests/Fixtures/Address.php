<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder\Tests\Fixtures;

use Cubiche\Core\Encoder\SerializableInterface;
use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Domain\Model\Entity;

/**
 * Address.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Address extends Entity implements SerializableInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $zipcode;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var Coordinate
     */
    protected $coordinate;

    /**
     * Address constructor.
     *
     * @param AddressId  $id
     * @param string     $name
     * @param string     $street
     * @param string     $zipcode
     * @param string     $city
     * @param Coordinate $coordinate
     */
    public function __construct(AddressId $id, $name, $street, $zipcode, $city, Coordinate $coordinate)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->street = $street;
        $this->zipcode = $zipcode;
        $this->city = $city;
        $this->coordinate = $coordinate;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function street()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function zipcode()
    {
        return $this->zipcode;
    }

    /**
     * @return string
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * @return Coordinate
     */
    public function coordinate()
    {
        return $this->coordinate;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return array(
            'id' => $this->id->toNative(),
            'name' => $this->name,
            'street' => $this->street,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'coordinate' => array(
                'lat' => $this->coordinate->latitude()->toNative(),
                'lng' => $this->coordinate->longitude()->toNative(),
            ),
        );
    }

    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self(
            AddressId::fromNative($data['id']),
            $data['name'],
            $data['street'],
            $data['zipcode'],
            $data['city'],
            Coordinate::fromLatLng($data['coordinate']['lat'], $data['coordinate']['lng'])
        );
    }
}
