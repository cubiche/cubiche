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

use Cubiche\Core\Visitor\DynamicDispatchVisitorInterface;
use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Dynamic Dispatch Visitor Interface Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class DynamicDispatchVisitorInterfaceTestCase extends VisitorInterfaceTestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(DynamicDispatchVisitorInterface::class)
        ;
    }

    /**
     * Test visit method.
     *
     * @param DynamicDispatchVisitorInterface $visitor
     * @param VisiteeInterface                $visitee
     * @param string                          $visitorMethod
     * @param mixed                           $expected
     *
     * @dataProvider visitDataProvider
     */
    public function testVisit(
        DynamicDispatchVisitorInterface $visitor,
        VisiteeInterface $visitee,
        $visitorMethod,
        $expected
    ) {
        $this
            ->given($visitor, $visitee)
            ->when($result = $visitor->visit($visitee))
            ->then()
                ->mock($visitor)
                    ->call($visitorMethod)
                        ->withArguments($visitee)
                            ->once()
                ->variable($result)
                    ->isEqualTo($expected);
    }

    /**
     * Test canHandlerVisitee method.
     *
     * @param DynamicDispatchVisitorInterface $visitor
     * @param VisiteeInterface                $visitee
     * @param bool                            $expected
     *
     * @dataProvider canHandlerVisiteeDataProvider
     */
    public function testCanHandlerVisitee(
        DynamicDispatchVisitorInterface $visitor,
        VisiteeInterface $visitee,
        $expected
    ) {
        $this
            ->given($visitor, $visitee)
            ->when($result = $visitor->canHandlerVisitee($visitee))
            ->then()
                ->boolean($result)
                    ->isEqualTo($expected)
        ;
    }

    /**
     * @return array
     */
    abstract protected function visitDataProvider();

    /**
     * @return array
     */
    abstract protected function canHandlerVisiteeDataProvider();
}
