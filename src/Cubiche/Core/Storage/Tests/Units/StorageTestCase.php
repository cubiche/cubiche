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

use Cubiche\Core\Storage\StorageInterface;
use Cubiche\Core\Storage\Tests\Fixtures\User;

/**
 * StorageTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class StorageTestCase extends TestCase
{
    /**
     * @return StorageInterface
     */
    abstract protected function createStorage();

    /**
     * Test get method.
     */
    public function testGetSet()
    {
        $this
            ->given($storage = $this->createStorage())
            ->and($user = new User('Ivan', 36, 'ivan@cubiche.org'))
            ->when($storage->set('foo', $user))
            ->then()
                ->object($storage->get('foo'))
                    ->isEqualTo($user)
                ->integer($storage->get('baz', 15))
                    ->isEqualTo(15)
        ;
    }

    /**
     * Test has method.
     */
    public function testHas()
    {
        $this
            ->given($storage = $this->createStorage())
            ->and($user = new User('Ivan', 36, 'ivan@cubiche.org'))
            ->when($storage->set('foo', $user))
            ->then()
                ->boolean($storage->has('foo'))
                    ->isTrue()
                ->boolean($storage->has('baz'))
                    ->isFalse()
        ;
    }

    /**
     * Test remove method.
     */
    public function testRemove()
    {
        $this
            ->given($storage = $this->createStorage())
            ->when(
                $storage->set('a', 'red'),
                $storage->set('b', 'blue')
            )
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
            ->given($storage = $this->createStorage())
            ->when(
                $storage->set('a', 'red'),
                $storage->set('b', 'blue')
            )
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
