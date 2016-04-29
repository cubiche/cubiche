<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Specification\Tests\Units\Constraint;

use Cubiche\Core\Equatable\Tests\Fixtures\EquatableObject;
use Cubiche\Core\Specification\Criteria;

/**
 * Not Equal Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotEqualTests extends BinaryConstraintOperatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitNotEqual';
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateSuccessDataProvider()
    {
        return array(
            array(4.999),
            array(6),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateFailureDataProvider()
    {
        return array(
            array(5),
            array(5.0),
        );
    }

    /**
     * Test evaluate Equatable instance.
     */
    public function testEvaluateEquatable()
    {
        $this
            ->given($neq = Criteria::neq(new EquatableObject(5)))
            ->then()
                ->boolean($neq->evaluate(new EquatableObject(5)))
                    ->isFalse()
                ->boolean($neq->evaluate(new EquatableObject(4)))
                    ->isTrue();
    }
}
