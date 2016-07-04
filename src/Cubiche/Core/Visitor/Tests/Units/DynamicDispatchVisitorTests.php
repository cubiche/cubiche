<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor\Tests\Units;

use Cubiche\Core\Visitor\Tests\Fixtures\Calculator;
use Cubiche\Core\Visitor\Tests\Fixtures\ExpressionToStringConverter;
use Cubiche\Core\Visitor\Tests\Fixtures\Mult;
use Cubiche\Core\Visitor\Tests\Fixtures\SmartExpressionToStringConverter;
use Cubiche\Core\Visitor\Tests\Fixtures\Sum;
use Cubiche\Core\Visitor\Tests\Fixtures\Value;
use Cubiche\Core\Visitor\Tests\Fixtures\Variable;

/**
 * Dynamic Dispatch Visitor Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DynamicDispatchVisitorTests extends DynamicDispatchVisitorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function visitDataProvider()
    {
        return array(
            array(
                $this->newMockInstance(Calculator::class),
                new Mult(new Value(3), new Value(2)),
                'visitMult',
                6,
            ),
            array(
                $this->newMockInstance(ExpressionToStringConverter::class),
                new Sum(new Value(1), new Value(2)),
                'visitOperator',
                '(1+2)',
            ),
            array(
                $this->newMockInstance(SmartExpressionToStringConverter::class),
                new Sum(new Value(1), new Value(2)),
                'visitOperator',
                '1+2',
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function canHandlerVisiteeDataProvider()
    {
        $data = parent::canHandlerVisiteeDataProvider();

        return \array_merge($data, array(
            array(new Calculator(), new Sum(new Value(1), new Value(2)), true),
            array(new Calculator(), new Variable('x'), false),
            array(new ExpressionToStringConverter(), new Sum(new Value(1), new Value(2)), true),
        ));
    }
}
