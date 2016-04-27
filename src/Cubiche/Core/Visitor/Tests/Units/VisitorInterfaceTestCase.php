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

use Cubiche\Tests\TestCase;
use Cubiche\Core\Visitor\VisitorInterface;
use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Visitor Interface Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class VisitorInterfaceTestCase extends TestCase
{
    /**
     * @return string
     */
    protected function visiteeInterface()
    {
        return VisiteeInterface::class;
    }

    /**
     * @return string
     */
    protected function shouldVisitMethod()
    {
        return;
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($visitor = $this->newDefaultTestedInstance())
            ->then()
                ->object($visitor)
                    ->isInstanceOf(VisitorInterface::class)
        ;
    }

    /**
     * Test visit.
     */
    public function testVisit()
    {
        $shouldVisitMethod = $this->shouldVisitMethod();
        if ($shouldVisitMethod !== null) {
            $this
                ->given($visiteeMock = $this->newMockVisiteeInterface())
                    ->calling($visiteeMock)
                        ->methods(
                            function ($method) use ($shouldVisitMethod) {
                                return $method === \strtolower($shouldVisitMethod);
                            }
                        )
                        ->return = 25
                ;

            $this
                /* @var \Cubiche\Core\Visitor\VisitorInterface $visitorMock */
                ->given($visitorMock = $this->newDefaultMockTestedInstance())
                ->when($result = $visitorMock->visit($visiteeMock))
                    ->mock($visiteeMock)
                        ->call($shouldVisitMethod)
                            ->withArguments($visitorMock)
                            ->once()
                    ->integer($result)
                        ->isEqualTo(25)
                ;
        } else {
            $this->skip(self::class.'::testVisit() skipped');
        }
    }

    /**
     * @return object
     */
    protected function newMockVisiteeInterface()
    {
        return $this->newMockInstance($this->visiteeInterface());
    }
}
