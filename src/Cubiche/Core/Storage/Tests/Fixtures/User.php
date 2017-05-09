<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Storage\Tests\Fixtures;

use Cubiche\Core\Equatable\Equatable;
use Cubiche\Core\Serializer\SerializableInterface;

/**
 * User Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class User extends Equatable implements SerializableInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $age;

    /**
     * @var string
     */
    protected $email;

    /**
     * @param string $name
     * @param int    $age
     * @param string $email
     */
    public function __construct($name, $age, $email)
    {
        $this->name = $name;
        $this->age = $age;
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function age()
    {
        return $this->age;
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
    public function email()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return $this->name() == $other->name() &&
            $this->age() == $other->age() &&
            $this->email() == $other->email()
        ;
    }
}
