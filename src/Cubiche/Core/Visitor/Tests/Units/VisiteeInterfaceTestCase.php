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

use Cubiche\Core\Visitor\VisiteeInterface;
use Cubiche\Core\Visitor\VisitorInterface;
use Cubiche\Tests\TestCase;

/**
 * Visitee Interface Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class VisiteeInterfaceTestCase extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(VisiteeInterface::class)
        ;
    }

    /**
     * Test accept.
     *
     * @param VisiteeInterface $visitee
     * @param VisitorInterface $visitorMock
     * @param string           $shouldVisitMethod
     * @param VisiteeInterface $withVisitee
     *
     * @dataProvider acceptVisitorDataProvider
     */
    public function testAcceptVisitor(VisiteeInterface $visitee, $visitorMock, $shouldVisitMethod, $withVisitee = null)
    {
        $this
            ->given($visitorMock, $shouldVisitMethod)
            ->calling($visitorMock)
                ->methods(
                    function ($method) use ($shouldVisitMethod) {
                        return $method === \strtolower($shouldVisitMethod);
                    }
                )
                ->return = 25
            ;

        $this
            ->given($visitee, $withVisitee = $withVisitee === null ? $visitee : $withVisitee)
            ->when($result = $visitee->accept($visitorMock))
            ->then()
                ->mock($visitorMock)
                    ->call($shouldVisitMethod)
                        ->withArguments($withVisitee)
                        ->once()
                ->integer($result)
                    ->isEqualTo(25)
        ;
    }

    /**
     * @return string[][]
     */
    protected function acceptVisitorDataProvider()
    {
        $visitee = $this->newDefaultTestedInstance();

        return array(
            array($visitee, $this->newMockInstance(VisitorInterface::class), 'visit'),
        );
    }
}
