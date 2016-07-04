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
use Cubiche\Core\Visitor\Tests\Fixtures\Evaluator;
use Cubiche\Core\Visitor\Tests\Fixtures\Sum;
use Cubiche\Core\Visitor\Tests\Fixtures\Value;
use Cubiche\Core\Visitor\Tests\Fixtures\Variable;

/**
 * Linked Visitor Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LinkedVisitorTests extends LinkedVisitorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function visitNextDataProvider()
    {
        return array(
            array(
                new Evaluator($calculator = $this->newMockInstance(Calculator::class)),
                $calculator,
                new Sum(new Value(1), new Value(2)),
                'visitSum',
                3,
            ),
            array(
                new Evaluator($calculator = $this->newMockInstance(Calculator::class), array('x' => 5)),
                $calculator,
                new Sum(new Value(1), new Variable('x')),
                'visitSum',
                6,
            ),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    protected function visitDataProvider()
    {
        return array(
            array(
                $this->newMockInstance(Evaluator::class, null, null, array(new Calculator(), array('x' => 5))),
                new Variable('x'),
                'visitVariable',
                5,
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
            array(new Evaluator(new Calculator()), new Sum(new Value(1), new Value(2)), true),
            array(new Evaluator(new Calculator(), array('x' => 5)), new Sum(new Variable('x'), new Value(2)), true),
        ));
    }
}
