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

use Cubiche\Core\Storage\AbstractStorage;
use Cubiche\Core\Storage\InMemoryMultidimensionalStorage;
use Cubiche\Core\Storage\MultidimensionalStorageInterface;

/**
 * InMemoryMultidimensionalStorageTests class.
 *
 * @method InMemoryMultidimensionalStorage newTestedInstance()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryMultidimensionalStorageTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->extends(AbstractStorage::class)
                ->implements(MultidimensionalStorageInterface::class)
        ;
    }

    /**
     * Test push method.
     */
    public function testPush()
    {
        $this
            ->given($storage = $this->newTestedInstance())
            ->when($storage->push('foo', 'bar'))
            ->then()
                ->array($storage->all('bar'))
                    ->isEmpty()
                ->integer($storage->count('bar'))
                    ->isEqualTo(0)
                ->array($storage->all('foo'))
                    ->hasSize(1)
                ->integer($storage->count('foo'))
                    ->isEqualTo(1)
            ->and()
            ->when($storage->push('foo', 'baz'))
            ->then()
                ->array($storage->all('foo'))
                    ->hasSize(2)
                    ->isIdenticalTo(array('bar', 'baz'))
                ->integer($storage->count('foo'))
                    ->isEqualTo(2)
        ;
    }

    /**
     * Test pop method.
     */
    public function testPop()
    {
        $this
            ->given($storage = $this->newTestedInstance())
            ->and($storage->push('foo', 'bar'))
            ->and($storage->push('foo', 478))
            ->and($storage->push('key', 'baz'))
            ->then()
                ->integer($storage->pop('foo'))
                    ->isEqualTo(478)
                ->string($storage->pop('foo'))
                    ->isEqualTo('bar')
                ->variable($storage->pop('foo'))
                    ->isNull()
                ->string($storage->pop('key'))
                    ->isEqualTo('baz')
                ->variable($storage->pop('key'))
                    ->isNull()
        ;
    }

    /**
     * Test slice method.
     */
    public function testSlice()
    {
        $this
            ->given($storage = $this->newTestedInstance())
            ->when($storage->push('foo', 'bar'))
            ->and($storage->push('foo', 'baz'))
            ->and($storage->push('foo', 'test'))
            ->then()
                ->array($storage->slice('foo', 1))
                    ->hasSize(2)
                    ->containsValues(array('baz', 'test'))
                ->array($storage->slice('foo', 3))
                    ->isEmpty()
                ->array($storage->slice('key', 0))
                    ->isEmpty()
        ;
    }
}
