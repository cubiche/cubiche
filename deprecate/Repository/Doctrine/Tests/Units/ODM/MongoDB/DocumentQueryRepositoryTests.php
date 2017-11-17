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
use Cubiche\Domain\Repository\Tests\Units\QueryRepositoryTestCase;
use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\DocumentQueryRepository;

/**
 * DocumentQueryRepositoryTests class.
 *
 * @engine isolate
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentQueryRepositoryTests extends QueryRepositoryTestCase
{
    use DocumentManagerTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function randomValue()
    {
        return new Address(
            AddressId::next(),
            'Address-'.\rand(1, 100),
            $this->faker->streetName,
            $this->faker->postcode,
            $this->faker->city,
            Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
        );

        $user = new User(UserId::next(), 'User-'.\rand(1, 100), \rand(1, 100), $this->faker->email);

        $user->setFax(new Phonenumber($this->faker->phoneNumber));
        foreach (range(1, 3) as $key) {
            $user->addPhonenumber(new Phonenumber($this->faker->phoneNumber));
        }

        $user->setMainRole($this->faker->randomElement(Role::values()));
        foreach (range(1, 3) as $key) {
            $user->addRole($this->faker->randomElement(Role::values()));
        }

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Home',
                $this->faker->streetAddress,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
            )
        );

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Work',
                $this->faker->streetAddress,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
            )
        );

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
        return new Address(
            AddressId::next(),
            'My Address',
            'My Street',
            'BG23',
            'Bigtown',
            Coordinate::fromLatLng(38.8951, -77.0364)
        );

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

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Home',
                $this->faker->streetAddress,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng(41.390205, 2.154007)
            )
        );

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Work',
                $this->faker->streetAddress,
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
        return new DocumentQueryRepository(
            $this->dm()->getRepository(Address::class),
            $this->documentDataSourceFactory()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function comparator()
    {
        return Comparator::by(Criteria::property('name'), Direction::DESC());
    }
}
