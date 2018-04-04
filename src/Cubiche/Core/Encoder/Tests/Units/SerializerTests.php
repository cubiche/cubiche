<?php

/**
 * This file is part of the Cubiche/Serializer component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder\Tests\Units;

use Cubiche\Core\Encoder\Handler\CollectionHandler;
use Cubiche\Core\Encoder\Handler\CoordinateHandler;
use Cubiche\Core\Encoder\Handler\DateRangeHandler;
use Cubiche\Core\Encoder\Handler\DateTimeHandler;
use Cubiche\Core\Encoder\Handler\DateTimeValueObjectHandler;
use Cubiche\Core\Encoder\Handler\HandlerManager;
use Cubiche\Core\Encoder\Handler\LocalizableValueHandler;
use Cubiche\Core\Encoder\Serializer;
use Cubiche\Core\Encoder\Tests\Fixtures\Address;
use Cubiche\Core\Encoder\Tests\Fixtures\AddressId;
use Cubiche\Core\Encoder\Tests\Fixtures\Phonenumber;
use Cubiche\Core\Encoder\Tests\Fixtures\Role;
use Cubiche\Core\Encoder\Tests\Fixtures\User;
use Cubiche\Core\Encoder\Tests\Fixtures\UserId;
use Cubiche\Core\Encoder\Visitor\DeserializationVisitor;
use Cubiche\Core\Encoder\Visitor\SerializationVisitor;
use Cubiche\Core\Encoder\Visitor\VisitorNavigator;
use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Domain\Geolocation\Coordinate;

/**
 * Serializer class.
 *
 * Generated by TestGenerator on 2016-05-03 at 14:37:10.
 */
class SerializerTests extends ClassMetadataFactoryTests
{
    /**
     * @return Serializer
     */
    protected function createSerializer()
    {
        $metadataFactory = $this->createFactory();

        $handlerManager = new HandlerManager();
        $eventBus = EventBus::create();

        // handlers
        $collectionHandler = new CollectionHandler();
        $dateHandler = new DateTimeHandler();
        $coordinateHandler = new CoordinateHandler();
        $localizableValueHandler = new LocalizableValueHandler();
        $dateTimeValueObjectHandler = new DateTimeValueObjectHandler();
        $dateRangeHandler = new DateRangeHandler();

        $handlerManager->registerSubscriberHandler($collectionHandler);
        $handlerManager->registerSubscriberHandler($dateHandler);
        $handlerManager->registerSubscriberHandler($coordinateHandler);
        $handlerManager->registerSubscriberHandler($localizableValueHandler);
        $handlerManager->registerSubscriberHandler($dateTimeValueObjectHandler);
        $handlerManager->registerSubscriberHandler($dateRangeHandler);

        $navigator = new VisitorNavigator($metadataFactory, $handlerManager, $eventBus);
        $serializationVisitor = new SerializationVisitor($navigator);
        $deserializationVisitor = new DeserializationVisitor($navigator);

        return new Serializer($navigator, $serializationVisitor, $deserializationVisitor);
    }

    /**
     * @return User
     */
    protected function createUser()
    {
        $user = new User(
            UserId::next(),
            'User-'.\rand(1, 100),
            array('en_US' => $this->faker->sentence, 'es_ES' => $this->faker->sentence),
            \rand(1, 100),
            $this->faker->email,
            array('en_US' => $this->faker->url, 'es_ES' => $this->faker->url),
            Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
        );

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
     * Test serialize/deserialize object.
     */
    public function testSerialize()
    {
        $this
            ->given($serializer = $this->createSerializer())
            ->and($user = $this->createUser())
            ->and($user1 = $this->createUser())
            ->then()
                ->variable($data = $serializer->serialize($user))
                    ->isNotNull()
                ->boolean($user->equals($serializer->deserialize($data, User::class)))
                    ->isTrue()
        ;
    }
}
