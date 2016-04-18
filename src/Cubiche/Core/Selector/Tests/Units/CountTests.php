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
 * Count Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CountTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitCount';
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            /* @var \Cubiche\Core\Selector\Count $count */
            ->given($count = $this->newTestedInstance())
            ->then()
                ->integer($count->apply(array(6, 7, 8, 9)))
                    ->isEqualTo(4)
                ->integer($count->apply(array(6, 7, 8, 9, 5)))
                    ->isEqualTo(5)
                ->integer($count->apply(array()))
                    ->isEqualTo(0)
        ;
    }
}
