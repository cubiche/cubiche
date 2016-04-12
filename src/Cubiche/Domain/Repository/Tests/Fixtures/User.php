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

use Cubiche\Domain\Model\AggregateRoot;
use Cubiche\Domain\Collections\ArrayCollectionInterface;
use Cubiche\Domain\Collections\ArrayCollection;

/**
 * User Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class User extends AggregateRoot
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
     * @var ArrayCollectionInterface
     */
    protected $phonenumbers;

    /**
     * @param UserId $id
     * @param string $name
     * @param int    $age
     */
    public function __construct(UserId $id, $name, $age)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->age = $age;
        $this->phonenumbers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function age()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return ArrayCollectionInterface
     */
    public function phonenumbers()
    {
        return $this->phonenumbers;
    }
}
