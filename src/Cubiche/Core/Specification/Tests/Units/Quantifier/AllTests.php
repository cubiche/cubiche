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
 * All Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class AllTests extends QuantifierTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Tests\TestCase::defaultConstructorArguments()
     */
    public function defaultConstructorArguments()
    {
        return array(Criteria::this(), Criteria::gt(5));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitAll';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\Tests\Units\SpecificationInterfaceTestCase::evaluateSuccessDataProvider()
     */
    protected function evaluateSuccessDataProvider()
    {
        return array(
            array(array(6, 7, 8, 9)),
            array(array()),
            array(6),
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
            array(array(6, 7, 8, 9, 5)),
            array(4),
        );
    }
}
