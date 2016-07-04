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
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(VisitorInterface::class)
        ;
    }

    /**
     * Test visit method.
     */
    public function testVisitUnsupportedVisitee()
    {
        $this
            ->given($visiteeMock = $this->newMockInstance(VisiteeInterface::class))
            /* @var \Cubiche\Core\Visitor\VisitorInterface $visitor */
            ->given($visitor = $this->newDefaultTestedInstance())
            ->exception(function () use ($visitor, $visiteeMock) {
                $visitor->visit($visiteeMock);
            })
                ->isInstanceOf(\LogicException::class)
        ;
    }
}
