<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Repository\Tests\Fixtures;

use Cubiche\Domain\Model\Entity;

/**
 * Address.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Address extends Entity
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
     * Address constructor.
     *
     * @param AddressId $id
     * @param string    $name
     * @param string    $street
     * @param string    $zipcode
     * @param string    $city
     */
    public function __construct(AddressId $id, $name, $street, $zipcode, $city)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->street = $street;
        $this->zipcode = $zipcode;
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
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
}
