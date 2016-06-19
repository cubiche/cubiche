<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Serializer\Tests\Fixtures;

/**
 * Address class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Address
{
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
     * Address constructor.
     *
     * @param string $street
     * @param string $zipcode
     * @param string $city
     */
    public function __construct($street, $zipcode, $city)
    {
        $this->setStreet($street);
        $this->setZipcode($zipcode);
        $this->setCity($city);
    }

    /**
     * @return string
     */
    public function zipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return string
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function street()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @param Address $other
     *
     * @return bool
     */
    public function equals(Address $other)
    {
        return $this->street() == $other->street() &&
            $this->zipcode() == $other->zipcode() &&
            $this->city() == $other->city()
        ;
    }
}
