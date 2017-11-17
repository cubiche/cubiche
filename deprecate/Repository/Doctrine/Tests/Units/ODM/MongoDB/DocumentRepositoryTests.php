<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Repository\Doctrine\Tests\Units\ODM\MongoDB;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\Direction;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Domain\Repository\Tests\Fixtures\Address;
use Cubiche\Domain\Repository\Tests\Fixtures\AddressId;
use Cubiche\Domain\Repository\Tests\Fixtures\Phonenumber;
use Cubiche\Domain\Repository\Tests\Fixtures\Role;
use Cubiche\Domain\Repository\Tests\Fixtures\User;
use Cubiche\Domain\Repository\Tests\Fixtures\UserId;
use Cubiche\Domain\Repository\Tests\Units\RepositoryTestCase;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * DocumentRepositoryTests class.
 *
 * @engine isolate
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentRepositoryTests extends RepositoryTestCase
{
    use DocumentManagerTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function randomValue()
    {
        $user = new User(UserId::next(), 'User-'.\rand(1, 100), \rand(1, 100), $this->faker->email);

        $user->setFax(new Phonenumber($this->faker->phoneNumber));
        foreach (range(1, 3) as $key) {
            $user->addPhonenumber(new Phonenumber($this->faker->phoneNumber));
        }

        $user->setMainRole($this->faker->randomElement(Role::values()));
        foreach (range(1, 3) as $key) {
            $user->addRole($this->faker->randomElement(Role::values()));
        }

//        $user->addAddress(
//            new Address(
//                AddressId::next(),
//                'Home',
//                $this->faker->streetAddress,
//                $this->faker->postcode,
//                $this->faker->city,
//                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
//            )
//        );

//        $user->addAddress(
//            new Address(
//                AddressId::next(),
//                'Work',
//                $this->faker->streetAddress,
//                $this->faker->postcode,
//                $this->faker->city,
//                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
//            )
//        );

        $user->setLanguageLevel('english', $this->faker->randomDigit);
        $user->setLanguageLevel('spanish', $this->faker->randomDigit);
        $user->setLanguageLevel('french', $this->faker->randomDigit);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        $user = new User(UserId::next(), 'Methuselah', 1000, $this->faker->email);

        $user->setFax(new Phonenumber('+34-208-1234567'));
        $user->addPhonenumber(new Phonenumber('+34 685 165 267'));
        $user->addPhonenumber(new Phonenumber($this->faker->phoneNumber));
        $user->addPhonenumber(new Phonenumber($this->faker->phoneNumber));

        $user->setMainRole(Role::ROLE_ADMIN());
        $user->addRole(Role::ROLE_ADMIN());
        $user->addRole(Role::ROLE_ADMIN());
        $user->addRole(Role::ROLE_USER());

        $user->setLanguageLevel('english', 10);
        $user->setLanguageLevel('spanish', 7);
        $user->setLanguageLevel('french', 2.5);

//        $user->addAddress(
//            new Address(
//                AddressId::next(),
//                'Home',
//                $this->faker->streetAddress,
//                $this->faker->postcode,
//                $this->faker->city,
//                Coordinate::fromLatLng(41.390205, 2.154007)
//            )
//        );

//        $user->addAddress(
//            new Address(
//                AddressId::next(),
//                'Work',
//                $this->faker->streetAddress,
//                $this->faker->postcode,
//                $this->faker->city,
//                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
//            )
//        );

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyRepository()
    {
        return new DocumentRepository($this->dm()->getRepository(User::class));
    }

    /**
     * {@inheritdoc}
     */
    protected function comparator()
    {
        return Comparator::by(Criteria::property('age'), Direction::DESC());
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        parent::testGet();

        $this
            ->given($repository = $this->randomRepository())
            ->and($unique = $this->uniqueValue())
            ->and($friend = new User(UserId::next(), 'Ivan', 32, $this->faker->email))
            ->and($friend1 = new User(UserId::next(), 'Karel', 32, $this->faker->email))
            ->and($repository->persist($friend))
            ->and($repository->persist($friend1))
            ->and($unique->addFriend($friend))
            ->and($unique->addFriend($friend1))
            ->and($repository->persist($unique))
            ->when(
                /** @var User $object */
                $object = $repository->get($unique->id())
            )
            ->then()
                ->object($object->fullName())
                    ->isEqualTo(StringLiteral::fromNative('Methuselah'))
                ->integer($object->languagesLevel()->get('english'))
                    ->isEqualTo(10)
                ->boolean($object->mainRole()->is(Role::ROLE_ADMIN))
                    ->isTrue()
                ->array($object->roles()->toArray())
                    ->contains(Role::ROLE_ADMIN)
                ->array($object->phonenumbers()->toArray())
                    ->contains('+34 685 165 267')
                ->string($object->fax()->number())
                    ->isEqualTo('+34-208-1234567')
                ->integer($object->friends()->count())
                    ->isEqualTo(2)
        ;
    }
}
