<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Selector\Tests\Units;

use Cubiche\Core\Selector\Field;

/**
 * Field Test Case class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class FieldTestCase extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    public function newDefaultTestedInstance()
    {
        return $this->newTestedInstance('foo');
    }

    /**
     * {@inheritdoc}
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($field = $this->newTestedInstance('foo'))
            ->then()
                ->object($field)
                    ->isInstanceOf(Field::class)
        ;
    }

    /**
     * Test name.
     */
    public function testName()
    {
        $this
            /* @var \Cubiche\Core\Selector\Field $field */
            ->given($field = $this->newTestedInstance('foo'))
            ->then()
                ->string($field->name())
                    ->isEqualTo('foo')
        ;
    }
}
