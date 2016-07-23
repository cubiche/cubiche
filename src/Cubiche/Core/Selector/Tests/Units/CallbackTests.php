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
 * Callback Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CallbackTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    public function newDefaultTestedInstance()
    {
        return $this->newTestedInstance(function ($value) {
            return $value + 1;
        });
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            /* @var \Cubiche\Core\Selector\Callback $callback */
            ->given($callback = $this->newDefaultTestedInstance())
            ->then()
                ->integer($callback->apply(1))
                    ->isEqualTo(2)
        ;
    }
}
