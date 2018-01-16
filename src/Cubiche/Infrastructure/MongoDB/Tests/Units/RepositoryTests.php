<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Tests\Units;

use Cubiche\Domain\Repository\Tests\Units\RepositoryTestCase as BaseRepositoryTestCase;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Infrastructure\MongoDB\Exception\MongoDBException;
use Cubiche\Infrastructure\MongoDB\Repository;
use Cubiche\Infrastructure\MongoDB\Tests\Fixtures\Address;
use Cubiche\Infrastructure\MongoDB\Tests\Fixtures\AddressId;
use Cubiche\Infrastructure\MongoDB\Tests\Fixtures\Phonenumber;
use Cubiche\Infrastructure\MongoDB\Tests\Fixtures\Role;
use Cubiche\Infrastructure\MongoDB\Tests\Fixtures\User;
use Cubiche\Infrastructure\MongoDB\Tests\Fixtures\UserId;

/**
 * RepositoryTests Class.
 *
 * @engine isolate
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RepositoryTests extends BaseRepositoryTestCase
{
    use DocumentManagerTestCaseTrait;

    /**
     * @return Repository
     */
    protected function emptyRepository()
    {
        return new Repository($this->dm(), User::class);
    }

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
    protected function comparator()
    {
        return Comparator::by(Criteria::property('age'));
    }

    /**
     * Test documentName.
     */
    public function testDocumentName()
    {
        $this
            ->given($repository = $this->emptyRepository())
            ->then()
                ->string($repository->documentName())
                    ->isEqualTo(User::class)
        ;
    }

    /**
     * Test persistAll.
     */
    public function testPersistAll()
    {
        parent::testPersistAll();

        $this
            ->given($repository = $this->randomRepository())
            ->and($id = UserId::next())
            ->then()
                ->exception(function () use ($repository, $id) {
                    $repository->persistAll([$id]);
                })->isInstanceOf(MongoDBException::class)
        ;
    }
}
