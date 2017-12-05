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
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RepositoryTests extends TestCase
{
    use DocumentManagerTestCaseTrait;

    /**
     * @param int $size
     *
     * @return Repository
     */
    protected function randomRepository($size = null)
    {
        $repository = $this->emptyRepository();
        $repository->persistAll($this->randomValues($size));

        return $repository;
    }

    /**
     * @return Repository
     */
    protected function emptyRepository()
    {
        return new Repository($this->dm(), User::class);
    }

    /**
     * @param int $size
     *
     * @return mixed[]
     */
    protected function randomValues($size = null)
    {
        $items = array();
        if ($size === null) {
            $size = \rand(10, 20);
        }
        foreach (\range(0, $size - 1) as $value) {
            $items[$value] = $this->randomValue();
        }

        return $items;
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
     * Test get.
     */
    public function testGet()
    {
        $this
            ->given($repository = $this->randomRepository())
            ->and($unique = $this->uniqueValue())
            ->when($repository->persist($unique))
            ->then()
                ->boolean($unique->equals($repository->get($unique->id())))
                    ->isTrue()
                ->string($repository->documentName())
                    ->isEqualTo(User::class)
        ;
    }

    /**
     * Test persist.
     */
    public function testPersist()
    {
        $this
            ->given($repository = $this->randomRepository())
            ->and($unique = $this->uniqueValue())
            ->then()
                ->variable($repository->get($unique->id()))
                    ->isNull()
            ->and()
            ->when($repository->persist($unique))
            ->then()
                ->boolean($unique->equals($repository->get($unique->id())))
                    ->isTrue()
        ;

        $this
            ->given($repository = $this->emptyRepository())
            ->and($value = $this->randomValue())
            ->and($age = $value->age())
            ->when(function () use ($repository, $value, $age) {
                $repository->persist($value);
                $value->setAge($age + 1);
                $repository->persist($value);
            })
            ->then()
                ->object($other = $repository->get($value->id()))
                    ->integer($other->age())
                        ->isEqualTo($age + 1)
        ;
    }

    /**
     * Test persistAll.
     */
    public function testPersistAll()
    {
        $this
            ->given($repository = $this->emptyRepository())
            ->and($unique = $this->uniqueValue())
            ->and($random = $this->randomValue())
            ->then()
                ->variable($repository->get($unique->id()))
                    ->isNull()
                ->variable($repository->get($random->id()))
                    ->isNull()
            ->and()
            ->when($repository->persistAll([$unique, $random]))
            ->then()
                ->boolean($unique->equals($repository->get($unique->id())))
                    ->isTrue()
                ->boolean($random->equals($repository->get($random->id())))
                    ->isTrue()
        ;

        $this
            ->given($repository = $this->randomRepository())
            ->given($id = UserId::next())
            ->then()
                ->exception(function () use ($repository, $id) {
                    $repository->persistAll([$id]);
                })->isInstanceOf(MongoDBException::class)
        ;

        $this
            ->given($repository = $this->emptyRepository())
            ->and($value = $this->randomValue())
            ->and($age = $value->age())
            ->when(function () use ($repository, $value, $age) {
                $repository->persistAll([$value]);
                $value->setAge($age + 1);
                $repository->persistAll([$value]);
            })
            ->then()
                ->object($other = $repository->get($value->id()))
                    ->integer($other->age())
                        ->isEqualTo($age + 1)
        ;
    }

    /**
     * Test remove.
     */
    public function testRemove()
    {
        $this
            ->given($repository = $this->randomRepository())
            ->and($unique = $this->uniqueValue())
            ->when($repository->persist($unique))
            ->then()
                ->boolean($unique->equals($repository->get($unique->id())))
                    ->isTrue()
            ->and()
            ->when($repository->remove($unique))
            ->then()
                ->variable($repository->get($unique->id()))
                    ->isNull()
        ;
    }
}
