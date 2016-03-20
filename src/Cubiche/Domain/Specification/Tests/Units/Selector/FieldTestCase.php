<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units\Selector;

use Cubiche\Domain\Specification\Selector\Field;

/**
 * FieldTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class FieldTestCase extends SelectorTestCase
{
    /*
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($specification = $this->randomSpecification())
            ->then
                ->object($specification)
                    ->isInstanceOf(Field::class)
        ;
    }

    /*
     * Test name.
     */
    public function testName()
    {
        $this
            ->given($specification = $this->randomSpecification('foo'))
            ->then()
                ->string($specification->name())
                    ->isEqualTo('foo')
        ;
    }
}
