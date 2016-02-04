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

use Cubiche\Domain\Storage\Exception\InvalidKeyException;
use Cubiche\Domain\Storage\StorageInterface;
use Cubiche\Domain\Tests\Units\TestCase;

/**
 * AbstractStorageTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AbstractStorageTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
            ->isAbstract()
            ->hasInterface(StorageInterface::class)
        ;
    }

    /**
     * Test validateKey method.
     */
    public function testValidateKey()
    {
        $this
            ->if($storage = new \mock\Cubiche\Domain\Storage\AbstractStorage())
            ->then
                ->boolean($this->invoke($storage)->validateKey(''))
                ->isTrue()
        ;

        $this
            ->if($storage = new \mock\Cubiche\Domain\Storage\AbstractStorage())
            ->then
                ->exception(
                    function () use ($storage) {
                        $this->invoke($storage)->validateKey(new \StdClass());
                    }
                )
                ->isInstanceOf(InvalidKeyException::class)
        ;
    }
}
