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

use Cubiche\Domain\Model\Tests\Fixtures\Category;
use Cubiche\Domain\Model\Tests\Fixtures\CategoryId;

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
            ->given($id = CategoryId::fromNative($this->faker->ean13()))
            ->and($entity = new Category($id))
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
            ->given($entity1 = new Category(CategoryId::fromNative($this->faker->unique()->uuid())))
            ->and($entity2 = new Category(CategoryId::fromNative($this->faker->ean13())))
            ->then()
                ->boolean($entity1->equals($entity1))
                    ->isTrue()
                ->boolean($entity1->equals($entity2))
                    ->isFalse()
        ;
    }
}
