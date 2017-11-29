<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Storage\Tests\Units;

use Cubiche\Core\Serializer\Encoder\ArrayEncoder;
use Cubiche\Core\Serializer\Encoder\DateTimeEncoder;
use Cubiche\Core\Serializer\Encoder\MetadataObjectEncoder;
use Cubiche\Core\Serializer\Encoder\NativeEncoder;
use Cubiche\Core\Serializer\Encoder\ObjectEncoder;
use Cubiche\Core\Serializer\Encoder\ValueObjectEncoder;
use Cubiche\Core\Serializer\Serializer;
use Cubiche\Core\Serializer\SerializerInterface;
use Cubiche\Core\Storage\InMemoryStorage;
use Cubiche\Core\Storage\StorageInterface;

/**
 * InMemoryStorageTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryStorageTests extends StorageTestCase
{
    use ClassMetadataFactoryTrait;

    /**
     * @return SerializerInterface
     */
    protected function createSerializer()
    {
        $metadataFactory = $this->createFactory();

        return new Serializer(array(
            new ValueObjectEncoder(),
            new DateTimeEncoder(),
            new MetadataObjectEncoder($metadataFactory),
            new ObjectEncoder(),
            new ArrayEncoder(),
            new NativeEncoder(),
        ));
    }

    /**
     * @return StorageInterface
     */
    protected function createStorage()
    {
        return new InMemoryStorage($this->createSerializer());
    }

    /**
     * Test get method.
     */
    public function testCreate()
    {
        $this
            ->given($storage = new InMemoryStorage($this->createSerializer(), array('foo' => 'bar')))
            ->then()
                ->string($storage->get('foo'))
                    ->isEqualTo('bar')
        ;
    }
}
