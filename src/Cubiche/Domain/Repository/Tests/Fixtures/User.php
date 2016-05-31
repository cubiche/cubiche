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

use Cubiche\Core\Collection\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collection\ArrayCollection\ArrayHashMapInterface;
use Cubiche\Core\Collection\ArrayCollection\ArrayList;
use Cubiche\Core\Collection\ArrayCollection\ArrayListInterface;
use Cubiche\Core\Collection\ArrayCollection\ArraySet;
use Cubiche\Core\Collection\ArrayCollection\ArraySetInterface;
use Cubiche\Domain\Model\AggregateRoot;

/**
 * User Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
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
     * @var ArrayListInterface
     */
    protected $phonenumbers;

    /**
     * @var ArraySetInterface
     */
    protected $roles;

    /**
     * @var ArrayHashMapInterface
     */
    protected $languagesLevel;

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
        $this->phonenumbers = new ArrayList();
        $this->roles = new ArraySet();
        $this->languagesLevel = new ArrayHashMap();
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
     * @return ArrayListInterface
     */
    public function phonenumbers()
    {
        return $this->phonenumbers;
    }

    /**
     * @param Phonenumber $phonenumber
     */
    public function addPhonenumber(Phonenumber $phonenumber)
    {
        return $this->phonenumbers->add($phonenumber);
    }

    /**
     * @return ArraySetInterface
     */
    public function roles()
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        return $this->roles->add($role);
    }

    /**
     * @return ArrayHashMapInterface
     */
    public function languagesLevel()
    {
        return $this->languagesLevel;
    }

    /**
     * @param string $language
     * @param int    $level
     *
     * @return ArrayHashMap|ArrayHashMapInterface
     */
    public function setLanguageLevel($language, $level)
    {
        return $this->languagesLevel->set($language, $level);
    }
}
