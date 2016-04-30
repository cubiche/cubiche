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

/**
 * Value Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ValueTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    public function newDefaultTestedInstance()
    {
        return $this->newTestedInstance(5);
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitValue';
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            /* @var \Cubiche\Core\Selector\Value $value */
            ->given($value = $this->newTestedInstance(5))
            ->then()
                ->integer($value->value())
                    ->isEqualTo(5)
                ->integer($value->value())
                    ->isEqualTo($value->apply(null))
        ;
    }
}
