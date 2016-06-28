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

use Cubiche\Core\Serializer\DefaultSerializer;
use Cubiche\Core\Storage\AbstractStorage;
use Cubiche\Core\Storage\InMemoryStorage;
use Cubiche\Core\Storage\StorageInterface;

/**
 * InMemoryStorageTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryStorageTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->extends(AbstractStorage::class)
                ->implements(StorageInterface::class)
        ;
    }

    /**
     * Test constructor.
     */
    public function testConstruct()
    {
        $this
            ->given($storage = new InMemoryStorage(new DefaultSerializer()))
            ->then()
                ->array($storage->keys())
                    ->isEmpty()
        ;
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $this
            ->given($storage = new InMemoryStorage(new DefaultSerializer()))
            ->if($storage->set('foo', 'bar'))
            ->then()
                ->boolean($storage->has('foo'))
                    ->isTrue()
        ;
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $this
            ->given($storage = new InMemoryStorage(new DefaultSerializer(), array('foo' => 'bar')))
            ->then()
                ->string($storage->get('foo'))
                    ->isEqualTo('bar')
                ->integer($storage->get('baz', 15))
                    ->isEqualTo(15)
        ;
    }

    /**
     * Test remove method.
     */
    public function testRemove()
    {
        $this
            ->given($storage = new InMemoryStorage(new DefaultSerializer(), array('a' => 'red', 'b' => 'blue')))
            ->then()
                ->boolean($storage->remove('a'))
                    ->isTrue()
                ->boolean($storage->remove('c'))
                    ->isFalse()
        ;
    }

    /**
     * Test clear method.
     */
    public function testClear()
    {
        $this
            ->given($storage = new InMemoryStorage(new DefaultSerializer(), array('a' => 'red', 'b' => 'blue')))
            ->then()
                ->array($storage->keys())
                    ->isNotEmpty()
                ->when($storage->clear())
                ->then()
                    ->array($storage->keys())
                        ->isEmpty()
        ;
    }
}
