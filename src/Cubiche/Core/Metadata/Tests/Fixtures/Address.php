<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures;

use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Core\Metadata\Tests\Fixtures\Annotations as Cubiche;

/**
 * Address class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @Cubiche\Entity
 */
class Address extends Entity
{
    /**
     * @var IdInterface
     * @Cubiche\Id
     */
    protected $id;

    /**
     * @var StringLiteral
     * @Cubiche\Field(type="StringLiteral")
     */
    protected $street;

    /**
     * @var StringLiteral
     * @Cubiche\Field(type="StringLiteral", name="zip")
     */
    protected $zipcode;

    /**
     * @var StringLiteral
     * @Cubiche\Field(type="StringLiteral")
     */
    protected $city;

    /**
     * Address constructor.
     *
     * @param AddressId $id
     * @param string    $street
     * @param string    $zipcode
     * @param string    $city
     */
    public function __construct(AddressId $id, $street, $zipcode, $city)
    {
        parent::__construct($id);

        $this->street = StringLiteral::fromNative($street);
        $this->zipcode = StringLiteral::fromNative($zipcode);
        $this->city = StringLiteral::fromNative($city);
    }

    /**
     * @return StringLiteral
     */
    public function street()
    {
        return $this->street;
    }

    /**
     * @return StringLiteral
     */
    public function zipcode()
    {
        return $this->zipcode;
    }

    /**
     * @return StringLiteral
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * @param Address $other
     *
     * @return bool
     */
    public function equals($other)
    {
        return $this->street() == $other->street() &&
            $this->zipcode() == $other->zipcode() &&
            $this->city() == $other->city()
        ;
    }
}
