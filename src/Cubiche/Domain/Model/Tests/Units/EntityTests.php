<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Units;

use Cubiche\Domain\Identity\StringId;
use Cubiche\Domain\Model\Tests\Fixtures\Category;

/**
 * EntityTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EntityTests extends TestCase
{
    /**
     * Test id method.
     */
    public function testId()
    {
        $this
            ->given($id = StringId::fromNative($this->faker->ean13()))
            ->and($entity = Category::create($id))
            ->then()
                ->boolean($entity->id()->equals($id))
                    ->isTrue()
        ;
    }

    /**
     * Test equals method.
     */
    public function testEquals()
    {
        $this
            ->given($id1 = StringId::fromNative($this->faker->unique()->uuid()))
            ->and($id2 = StringId::fromNative($this->faker->ean13()))
            ->and($entity1 = Category::create($id1))
            ->and($entity2 = Category::create($id2))
            ->then()
                ->boolean($entity1->equals($entity1))
                    ->isTrue()
                ->boolean($entity1->equals($entity2))
                    ->isFalse()
        ;
    }
}
