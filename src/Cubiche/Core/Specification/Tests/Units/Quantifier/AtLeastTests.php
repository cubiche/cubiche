<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification\Tests\Units\Quantifier;

use Cubiche\Core\Specification\Criteria;

/**
 * At Least Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class AtLeastTests extends QuantifierTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Tests\TestCase::defaultConstructorArguments()
     */
    public function defaultConstructorArguments()
    {
        return array(2, Criteria::this(), Criteria::gt(5));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitAtLeast';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\Tests\Units\SpecificationInterfaceTestCase::evaluateSuccessDataProvider()
     */
    protected function evaluateSuccessDataProvider()
    {
        return array(
            array(array(4, 6, 5, 3, 9)),
            array(array(4, 6, 5, 8, 9)),
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
            array(array(1, 4, 5, 3, 5)),
            array(array()),
        );
    }

    /**
     * Test evaluate at least zero.
     */
    public function testEvaluateAtLeastZero()
    {
        $this
        ->given($atLeast = $this->newTestedInstance(0, Criteria::this(), Criteria::gt(5)))
        ->then()
            ->boolean($atLeast->evaluate(array()))
                ->isTrue()
            ->boolean($atLeast->evaluate(array(1, 2, 3)))
                ->isTrue()
        ;
    }
}
