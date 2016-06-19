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

use Cubiche\Core\Serializer\SerializableInterface;

/**
 * Person class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Person implements SerializableInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Address
     */
    protected $address;

    /**
     * Person constructor.
     *
     * @param string  $name
     * @param Address $address
     */
    public function __construct($name, Address $address)
    {
        $this->setName($name);
        $this->setAddress($address);
    }

    /**
     * @return Address
     */
    public function address()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
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
     * @param Person $other
     *
     * @return bool
     */
    public function equals(Person $other)
    {
        return $this->address() == $other->address() &&
            $this->name() == $other->name()
        ;
    }
}
