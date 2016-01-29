<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model\Tests;

use Cubiche\Domain\Identity\SequenceId;
use Cubiche\Domain\Identity\HashId;

/**
 * Entity Test Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EntityTest extends TestCase
{
    /**
     * @test
     */
    public function id()
    {
        $id = HashId::fromNative('8743b52063cd84097a65d1633f5c74f5');

        $entity = $this->getMockBuilder('Cubiche\Domain\Model\Entity')
            ->setConstructorArgs(array($id))
            ->getMockForAbstractClass()
        ;

        $entity->expects($this->any())
            ->method('id')
            ->willReturn($id)
        ;

        $this->assertTrue($id->equals($entity->id()));
    }

    /**
     * @test
     */
    public function equals()
    {
        $id = SequenceId::fromNative('0078');

        $entity = $this->getMockBuilder('Cubiche\Domain\Model\Entity')
            ->setConstructorArgs(array($id))
            ->getMockForAbstractClass()
        ;

        $entity->expects($this->any())
            ->method('id')
            ->willReturn($id)
        ;

        $other = $this->getMockBuilder('Cubiche\Domain\Model\Entity')
            ->setConstructorArgs(array($id))
            ->getMockForAbstractClass()
        ;

        $other->expects($this->any())
            ->method('id')
            ->willReturn($id)
        ;

        $this->assertTrue($entity->equals($other));
    }
}
