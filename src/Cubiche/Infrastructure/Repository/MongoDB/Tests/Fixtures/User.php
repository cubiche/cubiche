<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Repository\MongoDB\Tests\Fixtures;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\ArrayHashMapInterface;
use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Collections\ArrayCollection\ArrayListInterface;
use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Core\Collections\ArrayCollection\ArraySetInterface;
use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * User Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class User extends AggregateRoot implements ReadModelInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var StringLiteral
     */
    protected $fullName;

    /**
     * @var int
     */
    protected $age;

    /**
     * @var EmailAddress
     */
    protected $email;

    /**
     * @var ArrayListInterface
     */
    protected $phonenumbers;

    /**
     * @var Phonenumber
     */
    protected $fax;

    /**
     * @var Role
     */
    protected $mainRole;

    /**
     * @var ArraySetInterface
     */
    protected $roles;

    /**
     * @var ArrayHashMapInterface
     */
    protected $languagesLevel;

    /**
     * @var ArraySetInterface
     */
    protected $addresses;

    /**
     * @param UserId $id
     * @param string $name
     * @param int    $age
     * @param string $email
     */
    public function __construct(UserId $id, $name, $age, $email)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->fullName = StringLiteral::fromNative($name);
        $this->age = $age;
        $this->email = EmailAddress::fromNative($email);
        $this->phonenumbers = array();
        $this->roles = new ArraySet();
        $this->languagesLevel = new ArrayHashMap();
        $this->addresses = new ArrayList();
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return StringLiteral
     */
    public function fullName()
    {
        return $this->fullName;
    }

    /**
     * @return EmailAddress
     */
    public function email()
    {
        return $this->email;
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
        $this->phonenumbers[] = $phonenumber;
    }

    /**
     * @return Phonenumber
     */
    public function fax()
    {
        return $this->fax;
    }

    /**
     * @param Phonenumber $fax
     */
    public function setFax(Phonenumber $fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return Role
     */
    public function mainRole()
    {
        return $this->mainRole;
    }

    /**
     * @param Role $mainRole
     */
    public function setMainRole(Role $mainRole)
    {
        $this->mainRole = $mainRole;
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

    /**
     * @return ArraySetInterface
     */
    public function addresses()
    {
        return $this->addresses;
    }

    /**
     * @param Address $address
     */
    public function addAddress(Address $address)
    {
        return $this->addresses->add($address);
    }
}
