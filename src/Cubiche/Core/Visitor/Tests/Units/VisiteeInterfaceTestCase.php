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
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($visitee = $this->newDefaultTestedInstance())
            ->then()
                ->object($visitee)
                    ->isInstanceOf(VisiteeInterface::class)
        ;
    }

    /**
     * Test accept.
     *
     * @param VisitorInterface $visitorMock
     * @param string           $shouldVisitMethod
     * @param string           $acceptVisitorMethod
     *
     * @dataProvider acceptVisitorDataProvider
     */
    public function testAcceptVisitor($visitorMock, $shouldVisitMethod, $acceptVisitorMethod)
    {
        $this
            ->given($visitorMock, $shouldVisitMethod, $acceptVisitorMethod)
            ->calling($visitorMock)
                ->methods(
                    function ($method) use ($shouldVisitMethod) {
                        return $method === \strtolower($shouldVisitMethod);
                    }
                )
                ->return = 25
            ;

        $this
            /* @var \Cubiche\Core\Visitor\VisiteeInterface $visitee */
            ->given($visitee = $this->newDefaultTestedInstance())
            ->when($result = $visitee->accept($visitorMock))
            ->then()
                ->mock($visitorMock)
                    ->call($shouldVisitMethod)
                        ->withArguments($visitee)
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
        return array(
            array($this->newMockVisitorInterface(), $this->shouldVisitMethod(), $this->acceptActualVisitorMethod()),
            array($this->newMockInstance(VisitorInterface::class), 'visit', 'accept'),
        );
    }

    /**
     * @return string
     */
    protected function visitorInterface()
    {
        return VisitorInterface::class;
    }

    /**
     * @return string
     */
    protected function shouldVisitMethod()
    {
        return 'visit';
    }

    /**
     * @return string
     */
    protected function acceptActualVisitorMethod()
    {
        return 'accept';
    }

    /**
     * @return object
     */
    protected function newMockVisitorInterface()
    {
        return $this->newMockInstance($this->visitorInterface());
    }
}
