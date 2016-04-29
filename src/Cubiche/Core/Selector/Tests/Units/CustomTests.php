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

use Cubiche\Core\Selector\Custom;

/**
 * Custom Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CustomTests extends SelectorTestCase
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
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitCustom';
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            /* @var \Cubiche\Core\Selector\Custom $custom */
            ->given($custom = $this->newDefaultTestedInstance())
            ->then()
                ->integer($custom->apply(1))
                    ->isEqualTo(2)
        ;
    }
}
