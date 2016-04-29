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
            ->given($this->newTestedInstance())
            ->then()
                ->array($this->testedInstance->keys())
                    ->isEmpty()
        ;
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $this
            ->given($this->newTestedInstance())
            ->if($this->testedInstance->set('foo', 'bar'))
            ->then()
                ->boolean($this->testedInstance->has('foo'))
                    ->isTrue()
        ;
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $this
            ->given($this->newTestedInstance(array('foo' => 'bar')))
            ->then()
                ->string($this->testedInstance->get('foo'))
                    ->isEqualTo('bar')
                ->integer($this->testedInstance->get('baz', 15))
                    ->isEqualTo(15)
        ;
    }

    /**
     * Test remove method.
     */
    public function testRemove()
    {
        $this
            ->given($this->newTestedInstance(array('a' => 'red', 'b' => 'blue')))
            ->then()
                ->boolean($this->testedInstance->remove('a'))
                    ->isTrue()
                ->boolean($this->testedInstance->remove('c'))
                    ->isFalse()
        ;
    }

    /**
     * Test clear method.
     */
    public function testClear()
    {
        $this
            ->given($this->newTestedInstance(array('a' => 'red', 'b' => 'blue')))
            ->then()
                ->array($this->testedInstance->keys())
                    ->isNotEmpty()
                ->when($this->testedInstance->clear())
                ->then()
                    ->array($this->testedInstance->keys())
                        ->isEmpty()
        ;
    }
}
