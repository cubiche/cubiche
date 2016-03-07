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

use Cubiche\Domain\Identity\AutoIncrementId;
use Cubiche\Domain\Identity\Id;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Tests\Units\TestCase;

/**
 * AutoIncrementIdTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AutoIncrementIdTests extends TestCase
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
            ->given($id = AutoIncrementId::fromNative(10))
            ->then
                ->integer($id->toNative())->isEqualTo(10)
                ->exception(
                    function () {
                        AutoIncrementId::fromNative('some-string');
                    }
                )->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
