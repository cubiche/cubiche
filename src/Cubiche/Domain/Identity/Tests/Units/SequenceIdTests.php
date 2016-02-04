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

use Cubiche\Domain\Identity\Id;
use Cubiche\Domain\Identity\SequenceId;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Tests\Units\TestCase;

class SequenceIdTests extends TestCase
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
            ->given($id = SequenceId::fromNative('000871667'))
            ->then
                ->string($id->toNative())->isEqualTo('000871667')
                ->exception(
                    function () {
                        SequenceId::fromNative(654.28);
                    }
                )->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
