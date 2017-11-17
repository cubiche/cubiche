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

use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\StringLiteral;

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
    protected $name;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var int
     */
    protected $number;

    /**
     * @var string
     */
    protected $zipcode;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var array
     */
    protected $contacts;

    /**
     * Address constructor.
     *
     * @param string $name
     * @param string $street
     * @param int    $number
     * @param string $zipcode
     * @param string $city
     * @param array  $contacts
     */
    public function __construct($name, $street, $number, $zipcode, $city, array $contacts = array())
    {
        $this->setName($name);
        $this->setStreet($street);
        $this->setNumber($number);
        $this->setZipcode($zipcode);
        $this->setCity($city);
        $this->setContacts($contacts);
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
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return int
     */
    public function number()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = Integer::fromNative($number);
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
     * @return StringLiteral
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
        $this->city = StringLiteral::fromNative($city);
    }

    /**
     * @return array
     */
    public function contacts()
    {
        return $this->contacts;
    }

    /**
     * @param array $contacts
     */
    public function setContacts(array $contacts)
    {
        $this->contacts = $contacts;
    }

    /**
     * @param Address $other
     *
     * @return bool
     */
    public function equals(Address $other)
    {
        return $this->name() == $other->name() &&
            $this->street() == $other->street() &&
            $this->number() == $other->number() &&
            $this->zipcode() == $other->zipcode() &&
            $this->city() == $other->city()
        ;
    }
}
