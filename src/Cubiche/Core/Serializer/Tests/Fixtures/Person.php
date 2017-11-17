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
use DateTime;

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
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Person constructor.
     *
     * @param string        $name
     * @param DateTime|null $createdAt
     */
    public function __construct($name, DateTime $createdAt = null)
    {
        $this->setName($name);
        $this->createdAt = $createdAt ? $createdAt : new DateTime();
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
     * @return \DateTime
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param Person $other
     *
     * @return bool
     */
    public function equals(Person $other)
    {
        return $this->name() == $other->name() &&
            $this->createdAt() == $other->createdAt()
        ;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return array(
            'name' => $this->name(),
            'createdAt' => $this->createdAt,
        );
    }

    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self($data['name'], $data['createdAt']);
    }
}
