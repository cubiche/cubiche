<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Storage\Tests\Units;

use Cubiche\Domain\Storage\AbstractStorage;
use Cubiche\Domain\Storage\Exception\KeyNotFoundException;
use Cubiche\Domain\Storage\StorageInterface;
use Cubiche\Domain\Tests\Units\TestCase;

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
            ->if($this->newTestedInstance())
            ->then
                ->array($this->testedInstance->keys())->isEmpty()
        ;
    }

    /**
     * Test set.
     */
    public function testSet()
    {
        $this
            ->given($this->newTestedInstance())
            ->if($this->testedInstance->set('foo', 'bar'))
            ->then
                ->boolean($this->testedInstance->exists('foo'))->isTrue()
        ;
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $this
            ->given($this->newTestedInstance(array('foo' => 'bar')))
            ->then
                ->string($this->testedInstance->get('foo'))->isEqualTo('bar')
                ->integer($this->testedInstance->get('baz', 15))->isEqualTo(15)
        ;
    }

    /**
     * Test get or fail.
     */
    public function testGetOrFail()
    {
        $this
            ->given($this->newTestedInstance(array('baz' => array('foo' => 'bar'))))
            ->then
                ->array($this->testedInstance->getOrFail('baz'))
                    ->string['foo']->isEqualTo('bar')
                ->exception(
                    function ($test) {
                        $test->testedInstance->getOrFail('invalid-key');
                    }
                )->isInstanceOf(KeyNotFoundException::class)
        ;
    }

    /**
     * Test get multiple.
     */
    public function testGetMultiple()
    {
        $this
            ->given($this->newTestedInstance(array('foo' => 42, 'bar' => 'value')))
            ->then
                ->array($this->testedInstance->getMultiple(array('foo', 'bar')))
                    ->integer['foo']->isEqualTo(42)
                    ->string['bar']->isEqualTo('value')
                ->array($this->testedInstance->getMultiple(array('foo', 'baz'), 'default'))
                    ->integer['foo']->isEqualTo(42)
                    ->string['baz']->isEqualTo('default')
        ;
    }

    /**
     * Test get multiple or fail.
     */
    public function testGetMultipleOrFail()
    {
        $this
            ->given($this->newTestedInstance(array('a' => 'red', 'b' => 'blue')))
            ->then
                ->array($this->testedInstance->getMultipleOrFail(array('a', 'b')))
                    ->string['a']->isEqualTo('red')
                    ->string['b']->isEqualTo('blue')
                ->exception(
                    function ($test) {
                        $test->testedInstance->getMultipleOrFail(array('a', 'invalid-key'));
                    }
                )->isInstanceOf(KeyNotFoundException::class)
        ;
    }

    /**
     * Test remove.
     */
    public function testRemove()
    {
        $this
            ->given($this->newTestedInstance(array('a' => 'red', 'b' => 'blue')))
            ->then
                ->boolean($this->testedInstance->remove('a'))->isTrue()
                ->boolean($this->testedInstance->remove('c'))->isFalse()
        ;
    }

    /**
     * Test clear.
     */
    public function testClear()
    {
        $this
            ->given($this->newTestedInstance(array('a' => 'red', 'b' => 'blue')))
            ->then
                ->array($this->testedInstance->keys())->isNotEmpty()
                ->when($this->testedInstance->clear())
                ->then
                    ->array($this->testedInstance->keys())->isEmpty()
        ;
    }
}
