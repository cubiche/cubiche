<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Specification\Tests\Units;

use Cubiche\Domain\Specification\AndSpecification;
use Cubiche\Domain\Specification\Constraint\Equal;
use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\Selector\This;

/**
 * AndSpecificationTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AndSpecificationTests extends SpecificationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification()
    {
        $left = new Equal(new This(), new Value(rand(1, 10)));
        $right = new LessThan(new This(), new Value(rand(1, 10)));

        return new AndSpecification($left, $right);
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitAnd';
    }

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
                    ->isInstanceOf(AndSpecification::class)
        ;
    }
}
