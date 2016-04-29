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
 * This Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ThisTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitThis';
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            ->given($selector = $this->newTestedInstance())
            ->then()
                ->boolean($selector->apply(true))
                    ->isTrue()
                ->boolean($selector->apply(false))
                    ->isFalse()
                ->object($selector->apply($this))
                    ->isEqualTo($this)
        ;
    }
}
