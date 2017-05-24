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

use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Core\Collections\ArrayCollection\ArraySetInterface;
use Cubiche\Core\Metadata\Tests\Fixtures\Annotations as Cubiche;
use Cubiche\Domain\Model\AggregateRoot;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * User Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @Cubiche\AggregateRoot
 */
class User extends AggregateRoot
{
    /**
     * @var IdInterface
     * @Cubiche\Id
     */
    protected $id;

    /**
     * @var StringLiteral
     * @Cubiche\Field(type="StringLiteral", name="fullName")
     */
    protected $name;

    /**
     * @var StringLiteral
     * @Cubiche\Field(type="StringLiteral")
     */
    protected $username;

    /**
     * @var int
     * @Cubiche\Field(type="Integer")
     */
    protected $age;

    /**
     * @var EmailAddress
     * @Cubiche\Field(type="EmailAddress")
     */
    protected $email;

    /**
     * @var ArraySetInterface
     * @Cubiche\Field(type="ArraySet", of="Cubiche\Core\Metadata\Tests\Fixtures\Address")
     */
    protected $addresses;

    /**
     * @var ArraySetInterface
     * @Cubiche\Field(type="ArraySet", of="Cubiche\Core\Metadata\Tests\Fixtures\User")
     */
    protected $friends;

    /**
     * @param UserId $id
     * @param string $name
     * @param string $username
     * @param int    $age
     * @param string $email
     */
    public function __construct(UserId $id, $name, $username, $age, $email)
    {
        parent::__construct($id);

        $this->name = StringLiteral::fromNative($name);
        $this->username = StringLiteral::fromNative($username);
        $this->age = Integer::fromNative($age);
        $this->email = EmailAddress::fromNative($email);
        $this->addresses = new ArraySet();
        $this->friends = new ArraySet();
    }

    /**
     * @return StringLiteral
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return StringLiteral
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function age()
    {
        return $this->age;
    }

    /**
     * @return EmailAddress
     */
    public function email()
    {
        return $this->email;
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

    /**
     * @return ArraySetInterface
     */
    public function friends()
    {
        return $this->friends;
    }

    /**
     * @param User $friend
     */
    public function addFriend(User $friend)
    {
        return $this->friends->add($friend);
    }
}
