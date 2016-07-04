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

use Cubiche\Core\Visitor\LinkedVisitor;
use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Linked Visitor Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class LinkedVisitorTestCase extends DynamicDispatchVisitorTestCase
{
    /**
     * Test visit method.
     *
     * @param LinkedVisitor $visitor
     * @param LinkedVisitor $next
     * @param VisiteeInterface $visitee
     * @param string $visitorMethod
     * @param mixed $expected
     *
     * @dataProvider visitNextDataProvider
     */
    public function testVisitNext(
        LinkedVisitor $visitor,
        LinkedVisitor $next,
        VisiteeInterface $visitee,
        $visitorMethod,
        $expected
    ) {
        $this
            ->given($visitor, $visitee)
            ->when($result = $visitor->visit($visitee))
            ->then()
                ->mock($next)
                    ->call($visitorMethod)
                        ->withArguments($visitee)
                            ->once()
                ->variable($result)
                    ->isEqualTo($expected)
        ;
    }
    
    /**
     * @return array
     */
    abstract protected function visitNextDataProvider();
}
