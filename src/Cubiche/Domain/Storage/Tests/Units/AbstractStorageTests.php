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
            ->given($this->newTestedInstance())
            ->then
                ->boolean($this->invoke($this->testedInstance)->validateKey(''))->isTrue()
                ->exception(
                    function () {
                        $this->invoke($this->testedInstance)->validateKey(new \stdClass());
                    }
                )->isInstanceOf(InvalidKeyException::class)
        ;
    }
}
