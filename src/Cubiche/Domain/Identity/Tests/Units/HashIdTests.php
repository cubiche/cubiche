<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Identity\Tests\Units;

use Cubiche\Domain\Identity\HashId;
use Cubiche\Domain\Identity\Id;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Tests\Units\TestCase;

/**
 * HashIdTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HashIdTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->extends(Id::class)
                ->implements(IdInterface::class)
        ;
    }

    /**
     * Test toNative method.
     */
    public function testToNative()
    {
        $this
            ->given($id = HashId::fromNative('GyuEmsRBfy61i59si0'))
            ->then
                ->string($id->toNative())->isEqualTo('GyuEmsRBfy61i59si0')
                ->exception(
                    function () {
                        HashId::fromNative(109);
                    }
                )->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
