<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Repository\MongoDB\Tests\Units;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\Direction;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Domain\Repository\Tests\Units\RepositoryTestCase;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Infrastructure\Repository\MongoDB\Tests\Fixtures\Address;
use Cubiche\Infrastructure\Repository\MongoDB\Tests\Fixtures\AddressId;
use Cubiche\Infrastructure\Repository\MongoDB\Tests\Fixtures\Phonenumber;
use Cubiche\Infrastructure\Repository\MongoDB\Tests\Fixtures\Role;
use Cubiche\Infrastructure\Repository\MongoDB\Tests\Fixtures\User;
use Cubiche\Infrastructure\Repository\MongoDB\Tests\Fixtures\UserId;

/**
 * DocumentRepositoryTests class.
 *
 * @engine isolate
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
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

        $user->setFax(Phonenumber::fromNative($this->faker->phoneNumber));
        $user->setMainRole(Role::ROLE_ADMIN());

        $user->addPhonenumber(Phonenumber::fromNative($this->faker->phoneNumber));
        $user->addPhonenumber(Phonenumber::fromNative($this->faker->phoneNumber));

        $user->addRole(Role::ROLE_ANONYMOUS());
        $user->addRole(Role::ROLE_USER());

        $user->setLanguageLevel('Spanish', 5);
        $user->setLanguageLevel('English', 4);
        $user->setLanguageLevel('Catalan', 3);

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Home',
                $this->faker->streetName,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
            )
        );

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Work',
                $this->faker->streetName,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
            )
        );

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        $user = new User(UserId::next(), 'Methuselah', 1000, $this->faker->email);

        $user->setFax(Phonenumber::fromNative('+34-208-1234567'));
        $user->setMainRole(Role::ROLE_ADMIN());

        $user->addPhonenumber(Phonenumber::fromNative('+34 685 165 267'));
        $user->addPhonenumber(Phonenumber::fromNative($this->faker->phoneNumber));

        $user->addRole(Role::ROLE_ANONYMOUS());
        $user->addRole(Role::ROLE_USER());

        $user->setLanguageLevel('Spanish', 5);
        $user->setLanguageLevel('English', 10);
        $user->setLanguageLevel('Catalan', 3);

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Home',
                $this->faker->streetName,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
            )
        );

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Work',
                $this->faker->streetName,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
            )
        );

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyRepository()
    {
        $documentRepositoryFactory = $this->createDocumentRepositoryFactory();

        return $documentRepositoryFactory->create(User::class);
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
            ->and($repository->persist($unique))
            ->when(
                /** @var User $object */
                $object = $repository->get($unique->id())
            )
            ->then()
                ->object($object->fullName())
                    ->isEqualTo(StringLiteral::fromNative('Methuselah'))
                ->integer($object->languagesLevel()->get('English'))
                    ->isEqualTo(10)
                ->boolean($object->mainRole()->is(Role::ROLE_ADMIN))
                    ->isTrue()
                ->array($object->roles()->toArray())
                    ->contains(Role::ROLE_ANONYMOUS)
                ->array($object->phonenumbers())
                    ->contains('+34 685 165 267')
                ->string($object->fax()->number())
                    ->isEqualTo('+34-208-1234567')
        ;
    }
}
