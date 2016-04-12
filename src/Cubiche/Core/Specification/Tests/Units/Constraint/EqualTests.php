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
 * Equal Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EqualTests extends BinaryConstraintOperatorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitEqual';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\Tests\Units\SpecificationInterfaceTestCase::evaluateSuccessDataProvider()
     */
    protected function evaluateSuccessDataProvider()
    {
        return array(
            array(5),
            array(5.0),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\Tests\Units\SpecificationInterfaceTestCase::evaluateFailureDataProvider()
     */
    protected function evaluateFailureDataProvider()
    {
        return array(
            array(4.999),
            array(6),
        );
    }

    /**
     * Test evaluate Equatable instance.
     */
    public function testEvaluateEquatable()
    {
        $this
            ->given($eq = Criteria::eq(new EquatableObject(5)))
            ->then()
                ->boolean($eq->evaluate(new EquatableObject(5)))
                    ->isTrue()
                ->boolean($eq->evaluate(new EquatableObject(5.0)))
                    ->isTrue()
                ->boolean($eq->evaluate(new EquatableObject(4)))
                    ->isFalse();
    }
}
