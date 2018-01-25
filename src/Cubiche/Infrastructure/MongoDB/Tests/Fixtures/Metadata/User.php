<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Tests\Fixtures\Metadata;

use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Collections\ArrayCollection\ArrayListInterface;
use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Core\Collections\ArrayCollection\ArraySetInterface;
use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\PropertyMetadata;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Cubiche\Infrastructure\MongoDB\Metadata\Annotations as Cubiche;

/**
 * User Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @Cubiche\AggregateRoot
 */
class User extends Entity
{
    /**
     * @var IdInterface
     * @Cubiche\Id(type="Cubiche\Infrastructure\MongoDB\Tests\Fixtures\Metadata\UserId")
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
     * @Cubiche\Field(type="EmailAddress", name="email_address")
     */
    protected $email;

    /**
     * @var ArrayListInterface
     * @Cubiche\Field(type="ArrayList[Cubiche\Infrastructure\MongoDB\Tests\Fixtures\Metadata\Address]")
     */
    protected $addresses;

    /**
     * @var ArraySetInterface
     * @Cubiche\Field(type="ArraySet[Cubiche\Infrastructure\MongoDB\Tests\Fixtures\Metadata\User]")
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
        $this->username = $username;
        $this->age = Integer::fromNative($age);
        $this->email = EmailAddress::fromNative($email);
        $this->addresses = new ArrayList();
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
     * @return string
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
     * @return ArrayListInterface
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

    /**
     * @param ClassMetadata $classMetadata
     */
    public static function loadMetadata(ClassMetadata $classMetadata)
    {
        // id
        $propertyMetadata = new PropertyMetadata(
            $classMetadata->className(),
            'id'
        );

        $propertyMetadata->addMetadata('identifier', true);
        $propertyMetadata->addMetadata('name', '_id');
        $classMetadata->addPropertyMetadata($propertyMetadata);

        // name
        $propertyMetadata = new PropertyMetadata(
            $classMetadata->className(),
            'name'
        );

        $propertyMetadata->addMetadata('identifier', false);
        $propertyMetadata->addMetadata('name', 'fullName');
        $propertyMetadata->addMetadata('type', 'StringLiteral');
        $classMetadata->addPropertyMetadata($propertyMetadata);

        // username
        $propertyMetadata = new PropertyMetadata(
            $classMetadata->className(),
            'username'
        );

        $propertyMetadata->addMetadata('identifier', false);
        $propertyMetadata->addMetadata('type', 'StringLiteral');
        $classMetadata->addPropertyMetadata($propertyMetadata);

        // age
        $propertyMetadata = new PropertyMetadata(
            $classMetadata->className(),
            'age'
        );

        $propertyMetadata->addMetadata('identifier', false);
        $propertyMetadata->addMetadata('type', 'Integer');
        $classMetadata->addPropertyMetadata($propertyMetadata);

        // email
        $propertyMetadata = new PropertyMetadata(
            $classMetadata->className(),
            'email'
        );

        $propertyMetadata->addMetadata('identifier', false);
        $propertyMetadata->addMetadata('type', 'EmailAddress');
        $classMetadata->addPropertyMetadata($propertyMetadata);

        // addresses
        $propertyMetadata = new PropertyMetadata(
            $classMetadata->className(),
            'addresses'
        );

        $propertyMetadata->addMetadata('identifier', false);
        $propertyMetadata->addMetadata('type', 'ArraySet');
        $propertyMetadata->addMetadata('of', 'Cubiche\Core\Metadata\Tests\Fixtures\Address');

        $classMetadata->addPropertyMetadata($propertyMetadata);

        // friends
        $propertyMetadata = new PropertyMetadata(
            $classMetadata->className(),
            'friends'
        );

        $propertyMetadata->addMetadata('identifier', false);
        $propertyMetadata->addMetadata('type', 'ArraySet');
        $propertyMetadata->addMetadata('of', 'Cubiche\Core\Metadata\Tests\Fixtures\User');

        $classMetadata->addPropertyMetadata($propertyMetadata);
    }
}
